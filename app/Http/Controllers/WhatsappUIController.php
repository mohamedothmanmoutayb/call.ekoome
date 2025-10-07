<?php

namespace App\Http\Controllers;

use App\Http\Services\WhatsAppService;
use App\Models\Citie;
use App\Models\Client;
use App\Models\Lead;
use App\Models\LeadProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\WhatsAppBusinessAccount;
use App\Models\WhatsAppConversation;
use App\Models\WhatsappLabel;
use App\Models\WhatsappLabelAssignment;
use App\Models\WhatsappMessage;
use App\Models\WhatsAppMessageTemplate;
use App\Services\WhatsAppCloudApiService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pusher\Pusher;

class WhatsappUIController extends Controller
{

    protected $whatsappService;

    public function __construct()
    {
        $this->whatsappService = new WhatsAppCloudApiService(env('WHATSAPP_ACCESS_TOKEN'));
    }

    public function index()
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->where('id_user', Auth::user()->id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->limit(50)->get();
        return view('backend.chats.whatsapp',compact('proo', 'cities'));
    }

    public function getAccounts()
    {
        $isAdmin = User::where('id', auth()->id())
            ->whereHas('rol', function ($query) {
                $query->where('name', 'admin');
            })
            ->exists();

        $accounts = WhatsAppBusinessAccount::first();
        $accounts = $isAdmin 
            ? WhatsappBusinessAccount::with('user')->get()
            : WhatsappBusinessAccount::where('user_id', auth()->id())->get();

            
        return response()->json([
            'success' => true,
            'accounts' => $accounts->map(function($account) {
                return [
                    'id' => $account->id,
                    'phone_number' => $account->phone_number,
                    'user_name' => $account->user->name ?? null
                ];
            })
        ]);
    }

    public function getConversations(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id'
        ]);
        
        $conversations = WhatsAppConversation::with('latestMessage')
            ->where('whatsapp_business_account_id', $request->account_id)
            ->withCount(['messages as unread_count' => function($query) {
                $query->where('read', false)->where('direction', 'in');
            }])
            ->get()
            ->sortByDesc(function($conversation) {
                return optional($conversation->latestMessage)->created_at;
            })
            ->values() 
            ->map(function($conversation) {
                $message = $conversation->latestMessage;

                if ($message && $message->deleted) {
                    $lastMessage = 'This message has been deleted';
                } elseif ($message && $message->body) {
                    $lastMessage = mb_substr($message->body, 0, 30);
                } else {
                    $lastMessage = 'Media message';
                }

                if ($message && $message->direction === 'out' && $message->status === 'failed') {
                    $lastMessage = '⚠️' . $lastMessage;
                }
                
                $data = [
                    'id' => $conversation->id,
                    'contact_number' => $conversation->contact_number,
                    'contact_name' => $conversation->contact_name,
                    'last_message' => $lastMessage,
                    'last_message_at' => $message->created_at ?? $conversation->updated_at,
                    'updated_at' => $conversation->updated_at,
                    'unread_count' => $conversation->unread_count,
                    'deleted' => $message->deleted ?? false,
                    'direction' => $message->direction ?? null,
                    'status' => $message->status ?? null,
                ];

                return $data;
            });

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    public function getMessages(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'conversation_id' => 'required|exists:whatsapp_conversations,id'
        ]);
        
        $conversation = WhatsAppConversation::where('whatsapp_business_account_id', $request->account_id)
            ->findOrFail($request->conversation_id);
            
        WhatsappMessage::where('whats_app_conversation_id', $conversation->id)
            ->where('read', false)
            ->where('direction', 'in')
            ->update(['read' => true]);
            
        $messages = WhatsappMessage::where('whats_app_conversation_id', $conversation->id)
            ->with(['media', 'quoted_message'])
            ->orderBy('created_at')
            ->get()
            ->map(function($message) use ($conversation) {
                $media = $message->media->map(function($mediaItem) {
                    return [
                        'id' => $mediaItem->id,
                        'file_path' => $mediaItem->file_path,
                        'mime_type' => $mediaItem->mime_type
                    ];
                });
                
                return [
                    'id' => $message->id,
                    'message_id' => $message->message_id,
                    'body' => $message->body,
                    'direction' => $message->direction,
                    'created_at' => $message->created_at,
                    'status' => $message->status,   
                    'date' => $message->created_at->format('Y-m-d'),
                    'deleted' => $message->deleted,
                    'type' => $message->type,
                    'template_name' => $message->template_name,
                    'error_data' => $message->error_data,
                    'template_data' => $message->template_data,
                    'quoted_message' => $message->quoted_message ? [
                        'id' => $message->quoted_message->id,
                        'body' => $message->quoted_message->body,
                        'direction' => $message->quoted_message->direction
                    ] : null,
                    'media' => $media,
                    'conversation' => [
                        'contact_name' => $conversation->contact_name,
                        'contact_number' => $conversation->contact_number,
                        'is_blocked' => $conversation->is_blocked
                    ]
                ];
            });
            
        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'contact_name' => $conversation->contact_name,
                'contact_number' => $conversation->contact_number
            ],
            'messages' => $messages
        ]);
    }

    public function getContactDetails($phoneNumber)
    {
        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            $client = Client::where('phone1', $cleanPhone)
                ->orWhere('phone2', $cleanPhone)
                ->first();
            $cities = Citie::where('id_country',Auth::user()->country_id)->get();
            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found'
                ]);
            }
            
            $conversation = WhatsappConversation::where('contact_number', $phoneNumber)->first();

            $labels = collect();

            if ($conversation) {
                $labels = WhatsappLabel::where('whatsapp_business_account_id', $conversation->whatsapp_business_account_id)
                    ->whereHas('conversations', function ($q) use ($conversation) {
                        $q->where('whatsapp_conversations.id', $conversation->id);
                    })
                    ->get(['id', 'name', 'color']); 
            }
            
            return response()->json([
                'success' => true,
                'client' => $client,
                'labels' => $labels,
                'cities' => $cities
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching contact details: ' . $e->getMessage()
            ]);
        }
    }

    public function updateContactDetails(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'phone1' => 'sometimes|string|max:20',
                'phone2' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'id_city' => 'nullable|exists:cities,id',
                'seller_note' => 'nullable|string'
            ]);
            
            $client->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Contact details updated successfully',
                'client' => $client
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating contact details: ' . $e->getMessage()
            ]);
        }
    }

    public function getTemplates(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'status' => 'sometimes|in:approved,pending,rejected,disabled'
        ]);
        
        $accountId = $request->input('account_id');
        $status = $request->input('status', 'approved');
        
        $templates = WhatsAppMessageTemplate::where('business_account_id', $accountId)
            ->where('status', $status)
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'templates' => $templates
        ]);
    }

    public function searchMessages(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'conversation_id' => 'required|exists:whatsapp_conversations,id',
            'search_term' => 'required|string|min:2',
            'filters' => 'sometimes|json'
        ]);

        try {
            $filters = json_decode($request->filters, true) ?? [];
            
            $query = WhatsAppMessage::where('whats_app_conversation_id', $request->conversation_id)
                ->where(function($query) use ($request, $filters) {
                    $searchTerm = $filters['matchCase'] ?? false ? $request->search_term : strtolower($request->search_term);
                    $query->where('body', 'like', '%' . $request->search_term . '%')
                        ->orWhereHas('media', function($q) use ($request) {
                            $q->where('caption', 'like', '%' . $request->search_term . '%');
                        });
                });

            if (!empty($filters['dateFrom'])) {
                $query->where('created_at', '>=', $filters['dateFrom']);
            }
            if (!empty($filters['dateTo'])) {
                $query->where('created_at', '<=', $filters['dateTo'] . ' 23:59:59');
            }

            if (($filters['sender'] ?? 'all') === 'me') {
                $query->where('direction', 'out');
            } elseif (($filters['sender'] ?? 'all') === 'them') {
                $query->where('direction', 'in');
            }

            $messages = $query->with(['media', 'conversation'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'results' => $messages->map(function($message) {
                    return [
                        'id' => $message->id,
                        'body' => $message->body,
                        'timestamp' => $message->created_at->format('H:i'),
                        'date' => $message->created_at->format('Y-m-d'),
                        'is_outgoing' => $message->direction === 'out',
                        'has_media' => $message->media->count() > 0,
                        'is_deleted' => $message->deleted,
                        'type' => $message->media->count() > 0 ? 'media' : 
                                ($message->template_name ? 'template' : 'text')
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }


    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'message' => 'required_without_all:media,audio',
            'media' => 'sometimes|file',
            'audio' => 'sometimes|file|mimes:mp3,aac,ogg',
            'contact_number' => 'required_without:conversation_id|string',
            'contact_name' => 'sometimes|string',
            'quoted_message_id' => 'sometimes|exists:whatsapp_business_messages,id',
            'conversation_id' => 'sometimes|exists:whatsapp_conversations,id'
        ]);

        $account = WhatsAppBusinessAccount::findOrFail($request->account_id);

        if ($request->has('conversation_id') && $request->conversation_id) {
            $conversation = WhatsAppConversation::where('id', $request->conversation_id)
                ->withCount(['messages as unread_count' => function ($query) {
                    $query->where('read', false)->where('direction', 'in');
                }])->firstOrFail();
        } else {
            if (!$request->has('contact_number') || empty($request->contact_number)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contact number is required for new conversations'
                ], 422);
            }

            $conversation = WhatsAppConversation::firstOrCreate(
                [
                    'whatsapp_business_account_id' => $account->id,
                    'contact_number' => $request->contact_number
                ],
                [
                    'contact_name' => $request->contact_name ?? $request->contact_number
                ]
            );

            $conversation->loadCount(['messages as unread_count' => function ($query) {
                $query->where('read', false)->where('direction', 'in');
            }]);
        }

        $mediaPath = null;
        $messageBody = $request->message;
        $mimeType = null;
        $response = null;
        $mediaId = null;


        if ($request->hasFile('media') || $request->hasFile('audio')) {
                $file = $request->hasFile('media') ? $request->file('media') : $request->file('audio');
                $mimeType = $file->getMimeType();
                
                $whatsappType = $this->getWhatsAppTypeFromMimeType($mimeType);
                
                $uploadRequest = new Request([
                    'media' => $file,
                    'account_id' => $account->id,
                    'type' => $whatsappType
                ]);
                
                $uploadResponse = $this->uploadMetaMedia($uploadRequest);

                if ($uploadResponse->getData()->success) {
                    $mediaId = $uploadResponse->getData()->media_id;
                    $mediaPath = $uploadResponse->getData()->local_path;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to upload media: ' . $uploadResponse->getData()->message
                    ], 500);
                }
        }

        $quotedMsgId = $request->quoted_message_id;
        $quotedMsg = $quotedMsgId ? WhatsappMessage::find($quotedMsgId) : null;

        if ($mediaPath) {
            $response = $this->whatsappService->sendMediaMessage(
                $account->phone_number_id,
                $this->formatPhoneNumberForWhatsApp($conversation->contact_number),
                $mediaPath,
                $mediaId,
                $mimeType,
                $messageBody 
            );
        } else {
            $response = $this->whatsappService->sendTextMessage(
                $account->phone_number_id,
                $this->formatPhoneNumberForWhatsApp($conversation->contact_number),
                $messageBody
            );
        }

        if ($request->hasFile('audio')) {
            $body = 'Audio message';
        } elseif ($request->hasFile('media')) {
            $mime = $mimeType;

            if (str_starts_with($mime, 'image/')) {
                $body = 'Photo' . ($messageBody ? ': ' . $messageBody : '');
            } elseif ($mime === 'application/pdf') {
                $body = 'PDF file' . ($messageBody ? ': ' . $messageBody : '');
            } elseif (str_starts_with($mime, 'video/')) {
                $body = 'Video' . ($messageBody ? ': ' . $messageBody : '');
            } elseif (str_starts_with($mime, 'application/')) {
                $body = 'Document' . ($messageBody ? ': ' . $messageBody : '');
            } else {
                $body = 'Attached file' . ($messageBody ? ': ' . $messageBody : '');
            }
        } else {
            $body = $messageBody;
        }

        $message = $conversation->messages()->create([
            'message_id' => $response['messages'][0]['id'] ?? Str::random(20),
            'from' => $account->phone_number,
            'to' => $conversation->contact_number,
            'body' => $body,
            'direction' => 'out',
            'status' => 'sent',
            'read' => true,
            'quoted_message_id' => $quotedMsgId
        ]);

        $extension = $this->getExtensionFromMimeType($mimeType);

        if ($mediaPath) {
            $extension = $this->getExtensionFromMimeType($mimeType);
            
            $message->media()->create([
                'media_id' => $mediaId, 
                'file_path' => $mediaPath, 
                'mime_type' => $mimeType,
                'extension' => $extension,
            ]);
        }

        $message->load('quoted_message');

        $conversation->loadCount(['messages as unread_count' => function ($query) {
            $query->where('read', false)->where('direction', 'in');
        }]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message_id' => $message->message_id,
                'body' => $message->body,
                'status' => $message->status,
                'direction' => $message->direction,
                'whats_app_conversation_id' => $message->whats_app_conversation_id,
                'created_at' => $message->created_at,
                'contact_name' => $conversation->contact_name,
                'contact_number' => $conversation->contact_number,
                'unread_count' => $conversation->unread_count,
                'error_data' => $message->error_data,
                'quoted_message' => $message->quoted_message ? [
                    'id' => $message->quoted_message->id,
                    'body' => $message->quoted_message->body,
                    'direction' => $message->quoted_message->direction
                ] : null,
                'media' => $message->media->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'file_path' => $media->file_path,
                        'mime_type' => $media->mime_type
                    ];
                })
            ],
        ]);
    }

    public function sendTemplate(Request $request)
    {
            $request->validate([
                'account_id' => 'required|exists:whatsapp_business_accounts,id',
                'conversation_id' => 'required|exists:whatsapp_conversations,id',
                'template_name' => 'required|string',
                'template_language' => 'required|string',
                'header_media' => 'required_if:header_format,IMAGE,VIDEO,DOCUMENT|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,pdf,doc,docx,txt|max:10240',
                'header_format' => 'sometimes|string',
                'header_variables' => 'sometimes|json',
                'body_variables' => 'sometimes|json',
                'limited_time_offer_variables' => 'sometimes|json',
                'button_variables' => 'sometimes|json',
                'footer' => 'sometimes|string'
            ]);

            try {
                $account = WhatsAppBusinessAccount::findOrFail($request->input('account_id'));
                $conversation = WhatsAppConversation::findOrFail($request->input('conversation_id'));
                
                $template = WhatsAppMessageTemplate::where('name', $request->input('template_name'))
                    ->where('language', $request->input('template_language'))
                    ->first();
                
                if (!$template) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Template not found'
                    ], 404);
                }
                
                $components = [];
                $buttonData = [];
                $offerData = [];
                
                $headerMediaId = null;
                $headerMediaPath = null;
                $headerFormat = $request->input('header_format');
                
                if ($request->hasFile('header_media') && $headerFormat) {
                    $file = $request->file('header_media');
                    $path = $file->store("headers/whatsapp_media/{$account->id}", 'public'); 
                    $headerMediaPath = storage_path("{$path}");

                    $headerMediaId = $this->uploadHeaderMedia(
                        $request->file('header_media'), 
                        $account->id, 
                        $headerFormat
                    );
                    
                    if ($headerMediaId) {
                        $headerParams = [[
                            'type' => strtolower($headerFormat),
                            strtolower($headerFormat) => ['id' => $headerMediaId]
                        ]];
                        
                        $components[] = [
                            'type' => 'header', 
                            'parameters' => $headerParams
                        ];
                    }
                }
                
                if ($request->has('header_variables')) {
                    $headerVariables = json_decode($request->input('header_variables'), true);
                    if (!empty($headerVariables)) {
                        $headerParams = [];
                        
                        foreach ($headerVariables as $var) {
                            if (!isset($var['value']) || empty($var['value'])) continue;
                            
                            $headerParams[] = [
                                'type' => 'text',
                                'text' => $var['value']
                            ];
                        }
                        
                        if (!empty($headerParams)) {
                            $components[] = ['type' => 'header', 'parameters' => $headerParams];
                        }
                    }
                }
                
                if ($request->has('body_variables')) {
                    $bodyVariables = json_decode($request->input('body_variables'), true);
                    if (!empty($bodyVariables)) {
                        $bodyParams = [];
                        
                        foreach ($bodyVariables as $var) {
                            if (!isset($var['value']) || empty($var['value'])) continue;
                            
                            $bodyParams[] = [
                                'type' => 'text',
                                'text' => $var['value']
                            ];
                        }
                        
                        if (!empty($bodyParams)) {
                            $components[] = ['type' => 'body', 'parameters' => $bodyParams];
                        }
                    }
                }
                
                if ($request->has('limited_time_offer_variables')) {
                    $offerVariables = json_decode($request->input('limited_time_offer_variables'), true);
                    if (!empty($offerVariables)) {
                        $offerParams = [];
                        
                        foreach ($offerVariables as $var) {
                            if (!isset($var['value']) || empty($var['value'])) continue;

                            $offerData[] = $var;
                            
                            $offerParams[] = [
                                'type' => 'limited_time_offer',
                                'limited_time_offer' => [
                                    'expiration_time_ms' => (int)$var['value']
                                ]
                            ];
                        }
                        
                        if (!empty($offerParams)) {
                            $components[] = ['type' => 'limited_time_offer', 'parameters' => $offerParams];
                        }
                    }
                }
                
                if ($request->has('button_variables')) {
                    $buttonVariables = json_decode($request->input('button_variables'), true);
                    if (!empty($buttonVariables)) {
                        $buttonComponents = [];
                        
                        foreach ($buttonVariables as $var) {
                            if (!isset($var['value']) || empty($var['value'])) continue;

                            $buttonData[] = $var;
                            
                            $buttonType = strtolower($var['buttonType'] ?? '');
                            $buttonIndex = $var['buttonIndex'] ?? 0;
                            
                            $param = [];
                            
                            switch ($buttonType) {
                                case 'url':
                                    $param = [
                                        'type' => 'text',
                                        'text' => $var['value']
                                    ];
                                    break;
                                case 'copy_code':
                                case 'copy_code':
                                    $param = [
                                        'type' => 'coupon_code',
                                        'coupon_code' => $var['value']
                                    ];
                                    break;
                                case 'phone_number':
                                case 'phone_number':
                                    $param = [
                                        'type' => 'text',
                                        'text' => $var['value']
                                    ];
                                    break;
                                default:
                                    $param = [
                                        'type' => 'text',
                                        'text' => $var['value']
                                    ];
                            }
                            
                            if (!isset($buttonComponents[$buttonIndex])) {
                                $buttonComponents[$buttonIndex] = [
                                    'type' => 'button',
                                    'sub_type' => $buttonType,
                                    'index' => $buttonIndex,
                                    'parameters' => []
                                ];
                            }
                            
                            $buttonComponents[$buttonIndex]['parameters'][] = $param;
                        }
                        
                        foreach ($buttonComponents as $buttonComponent) {
                            $components[] = $buttonComponent;
                        }
                    }
                }
                
                if ($request->has('footer') && !empty($request->input('footer'))) {
                    $components[] = [
                        'type' => 'footer',
                        'parameters' => [[
                            'type' => 'text',
                            'text' => $request->input('footer')
                        ]]
                    ];
                }
                
                $payload = [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $conversation->contact_number,
                    'type' => 'template',
                    'template' => [
                        'name' => $request->input('template_name'),
                        'language' => ['code' => $request->input('template_language')]
                    ]
                ];
                
                if (!empty($components)) {
                    $payload['template']['components'] = $components;
                }
                
                \Log::info('WhatsApp Template Payload:', $payload);

                $response = Http::withToken($account->getDecryptedAccessTokenAttribute())
                    ->timeout(30)
                    ->post("https://graph.facebook.com/v23.0/{$account->phone_number_id}/messages", $payload);
                    
                    if ($response->successful()) {
                        $responseData = $response->json();
                        
                        $message = new WhatsAppMessage();
                        $message->whats_app_conversation_id = $conversation->id;
                        $message->message_id = $responseData['messages'][0]['id'];
                        $message->from = $account->phone_number;
                        $message->to = $conversation->contact_number;
                        $message->body = "Template: " . $request->input('template_name');
                        $message->direction = 'out';
                        $message->status = 'sent';
                        $message->type = 'template';
                        $message->template_name = $request->input('template_name');

                        $templateData = [
                            'header' => [
                                'type' => $headerFormat,
                                'text' => $request->input('header_text') ?? null,
                                'image' => $$headerMediaPath ?? null,
                            ],
                            'body' => $template->body,
                            'footer' => $request->input('footer') ?? $template->footer,
                            'buttons' => $buttonData,
                            'limited_time_offer' => $offerData
                        ];


                        $message->template_data = json_encode($templateData);
                        $message->save();
                        
                        
                        $this->broadcastNewMessage($account,$message,$conversation);
                        
                        return response()->json([
                            'success' => true,
                            'message' => [
                                'id' => $message->id,
                                'message_id' => $message->message_id,
                                'body' => $message->body,
                                'status' => $message->status,
                                'direction' => $message->direction,
                                'whats_app_conversation_id' => $message->whats_app_conversation_id,
                                'created_at' => $message->created_at,
                                'contact_name' => $conversation->contact_name,
                                'contact_number' => $conversation->contact_number,
                                'unread_count' => $conversation->unread_count,
                                'template_data' => $templateData,
                                'error_data' => $message->error_data,
                                'type' => $message->type,
                                'templae_name' => $message->template_name,
                                'quoted_message' => $message->quoted_message ? [
                                    'id' => $message->quoted_message->id,
                                    'body' => $message->quoted_message->body,
                                    'direction' => $message->quoted_message->direction
                                ] : null,
                            ]
                        ]);
                    } else {
                        $errorResponse = $response->json();
                        \Log::error('WhatsApp API Error:', [
                            'response' => $errorResponse,
                            'status' => $response->status()
                        ]);
                        
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to send template message: ' . 
                                        ($errorResponse['error']['message'] ?? $response->body())
                        ], 400);
                    }
                } catch (\Exception $e) {
                    \Log::error('Template Send Error:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Error sending template message: ' . $e->getMessage()
                    ], 500);
                }
    }

    private function uploadHeaderMedia($file, $accountId, $format)
    {
        $mimeType = $file->getMimeType();
        $whatsappType = $this->getWhatsAppTypeFromMimeType($mimeType);
        
        $uploadRequest = new Request([
            'media' => $file,
            'account_id' => $accountId,
            'type' => $whatsappType
        ]);
        
        $uploadResponse = $this->uploadMetaMedia($uploadRequest);

        if ($uploadResponse->getData()->success) {
            return $uploadResponse->getData()->media_id;
        }
        
        return null;
    }

    private function getWhatsAppTypeFromMimeType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        
        return 'document';
    }


    private function formatPhoneNumberForWhatsApp($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (!preg_match('/^[1-9][0-9]{7,14}$/', $phoneNumber)) {
        }
        
        return $phoneNumber;
    }

    public function uploadMetaMedia(Request $request)
    {
        $request->validate([
            'media' => 'required|file|max:102400', 
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'type' => 'required|in:image,video,audio,document'
        ]);

        try {
            $businessAccount = WhatsAppBusinessAccount::findOrFail($request->account_id);
            $accessToken = decrypt($businessAccount->access_token);
            $file = $request->media;
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            $path = $file->store('whatsapp_media', 'public');


            $absolutePath = Storage::disk('public')->path($path);

            $sessionResponse = Http::withToken($accessToken)
                ->attach(
                    'file', 
                    fopen($absolutePath, 'r'), 
                    $fileName,
                    ['Content-Type' => $mimeType]
                )
                ->post("https://graph.facebook.com/v23.0/{$businessAccount->phone_number_id}/media", [
                    'messaging_product' => 'whatsapp',
                    'type' => $mimeType,
                ]);

            if (!$sessionResponse->successful()) {
                Storage::disk('public')->delete($path);
                throw new \Exception('Failed to upload media: ' . $sessionResponse->body());
            }

            $responseData = $sessionResponse->json();
            $mediaId = $responseData['id'];

            return response()->json([
                'success' => true,
                'media_id' => $mediaId,
                'local_path' => $path,
                'mime_type' => $mimeType,
                'file_name' => $fileName
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Media upload error:', [
                'error' => $e->getMessage(),
                'account_id' => $request->account_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
        

    public function markAsRead($conversationId)
    {
        WhatsappMessage::where('whats_app_conversation_id', $conversationId)
            ->where('read', false)
            ->where('direction', 'in')
            ->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function getFile($filename)
    {
        $path = storage_path('app/public/whatsapp-media/' . $filename);
    
        if (!file_exists($path)) {
            abort(404);
        }
    
        $file = file_get_contents($path);
        $type = mime_content_type($path);
    
        return response($file, 200)
            ->header('Content-Type', $type);
    }

    protected function getExtensionFromMimeType($mimeType)
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'video/mp4' => 'mp4',
            'audio/mp3' => 'mp3',
            'audio/aac' => 'aac',
            'audio/ogg' => 'ogg',
            'application/pdf' => 'pdf',
        ];
        
        return $extensions[$mimeType] ?? 'bin';
    }

    private function broadcastNewMessage($account,$message, $conversation)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $message->load('media');

        $pusher->trigger(
            'whatsapp.'.$account->id,
            'NewMessage',
            [
                'message' => $message,
                'conversation_id' => $conversation->id,
                'contact_number' => $conversation->contact_number,
                'contact_name' => $conversation->contact_name
            ]
        );
    }

    public function checkConversation(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'contact_number' => 'required|string',
            'contact_name' => 'sometimes|string'
        ]);

        $account = WhatsAppBusinessAccount::findOrFail($request->account_id);
        
        $conversation = WhatsAppConversation::firstOrCreate(
            [
                'whatsapp_business_account_id' => $account->id,
                'contact_number' => $request->contact_number
            ],
            [
                'contact_name' => $request->contact_name ?? $request->contact_number
            ]
        );

        $client = Client::firstOrCreate(
        [
            'phone1' => $request->contact_number,  
        ],
        [
            'id_user'   => $account->user['id'], 
            'name'      => $request->contact_name ?? null,
            'id_country'=> $account->user['country_id'] ?? null, 
            'city'      => null,
            'phone2'    => null,
            'address'   => null,
            'seller_note' => null,
        ]
        );

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'contact_name' => $conversation->contact_name,
                'contact_number' => $conversation->contact_number
            ]
        ]);
    }

    public function checkContact(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'contact_number' => 'required|string',
            'conversation_id' => 'nullable|exists:whatsapp_conversations,id'
        ]);

        $query = WhatsAppConversation::where('whatsapp_business_account_id', $request->account_id)
                    ->where('contact_number', $request->contact_number);


        if ($request->has('conversation_id')) {
            $query->where('id', '!=', $request->conversation_id);
        }

        return response()->json([
            'exists' => $query->exists()
        ]);
    }

    public function updateContact(Request $request, WhatsAppConversation $conversation)
    {
        $request->validate([
            'contact_name' => 'required|string',
        ]);

        if (!auth()->user()->whatsappBusinessAccounts->contains($conversation->whatsapp_business_account_id)) {
            abort(403);
        }

        $conversation->update([
            'contact_name' => $request->contact_name,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyConversation(WhatsAppConversation $conversation)
    {
        // if (!auth()->user()->whatsappBusinessAccounts->contains($conversation->whatsapp_business_account_id)) {
        //     abort(403);
        // }

        // $conversation->delete();

        return response()->json(['success' => true]);
    }

    public function deleteMessage(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'message_id' => 'required|string|exists:whatsapp_business_messages,message_id'
        ]);
        
        try {
            $account = WhatsAppBusinessAccount::findOrFail($request->account_id);
            $message = WhatsAppMessage::where('message_id', $request->message_id)->firstOrFail();

            $apiResponse = $this->whatsappService->deleteMessage(
                $account->phone_number_id,
                decrypt($account->access_token),
                $request->message_id
            );

            if (($apiResponse['status'] ?? '') === 'pending') {
                $message->deleted = true;
                $message->save();
                
                $this->broadcastMessageUpdate($message);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Message deletion request sent successfully',
                    'status' => 'pending'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $apiResponse['error'] ?? $apiResponse['message'] ?? 'Failed to delete message via API',
                'status' => $apiResponse['status'] ?? 'failed'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting message: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    private function broadcastMessageUpdate($message, $conversation = null)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $pusher->trigger(
            'whatsapp.' . $message->conversation->whatsapp_business_account_id,
            'MessageUpdate',
            [
                'id' => $message->id,
                'conversation_id' => $message->whats_app_conversation_id,
                'contact_number' => $conversation->contact_number,
                'contact_name' => $conversation->contact_name
            ]
        );
    }

    public function blockConversation(Request $request, WhatsAppConversation $conversation)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id'
        ]);

        $account = WhatsAppBusinessAccount::findOrFail($request->account_id);

        try {
            $response = $this->whatsappService
                ->setAccessToken(decrypt($account->access_token))
                ->blockUsers(
                    $account->phone_number_id,
                    [$this->formatWhatsAppNumber($conversation->contact_number)]
                );
            $blockedSuccessfully = false;
            foreach ($response['block_users']['added_users'] ?? [] as $addedUser) {
                if ($addedUser['input'] === $conversation->contact_number || 
                    $addedUser['wa_id'] === $conversation->contact_number) {
                    $blockedSuccessfully = true;
                    break;
                }
            }

            if ($blockedSuccessfully) {
                $conversation->update(['is_blocked' => true]);
                return response()->json([
                    'success' => true,
                    'message' => 'Contact blocked successfully'
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => 'Failed to block contact via WhatsApp API'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Block contact error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error blocking contact: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unblockConversation(Request $request, WhatsAppConversation $conversation)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id'
        ]);

        $account = WhatsAppBusinessAccount::findOrFail($request->account_id);

        try {
            $response = $this->whatsappService
                ->setAccessToken(decrypt($account->access_token))
                ->unblockUsers(
                    $account->phone_number_id,
                    [$this->formatWhatsAppNumber($conversation->contact_number)]
                );

            $unblockedSuccessfully = false;
            foreach ($response['block_users']['removed_users'] ?? [] as $removedUser) {
                if ($removedUser['input'] === $conversation->contact_number || 
                    $removedUser['wa_id'] === $conversation->contact_number) {
                    $unblockedSuccessfully = true;
                    break;
                }
            }

            if ($unblockedSuccessfully) {
                $conversation->update(['is_blocked' => false]);
                return response()->json([
                    'success' => true,
                    'message' => 'Contact unblocked successfully'
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => 'Failed to unblock contact via WhatsApp API'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Unblock contact error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error unblocking contact: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getBlockedUsers(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id'
        ]);

        $account = WhatsAppBusinessAccount::findOrFail($request->account_id);

        try {
            $blockedUsers = $this->whatsappService
                ->setAccessToken(decrypt($account->access_token))
                ->getBlockedUsers($account->phone_number_id, $request->get('limit', 50));

            return response()->json([
                'success' => true,
                'blocked_users' => $blockedUsers
            ]);

        } catch (\Exception $e) {
            \Log::error('Get blocked users error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error retrieving blocked users: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getLabels(Request $request)
    {
        $request->validate([    
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'with_counts' => 'sometimes|in:true,false,1,0',
            'conversation_id' => 'sometimes|exists:whatsapp_conversations,id'
        ]);
        
        $query = WhatsappLabel::where('whatsapp_business_account_id', $request->account_id);
        
        if ($request->with_counts) {
            $query->withCount('conversations');
        }
        
        if ($request->conversation_id) {
            $query->with(['conversations' => function($q) use ($request) {
                $q->where('whatsapp_conversations.id', $request->conversation_id);
            }]);
            
            $labels = $query->get()->map(function($label) {
                $label->assigned = $label->conversations->isNotEmpty();
                return $label;
            });
        } else {
            $labels = $query->get();
        }
            
        return response()->json([
            'success' => true,
            'labels' => $labels
        ]);
    }

    public function createLabel(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'name' => 'required|string|max:255',
            'color' => 'sometimes|string|max:7'
        ]);
        
        $label = WhatsappLabel::create([
            'name' => $request->name,
            'color' => $request->color ?? '#7e8da1',
            'user_id' => auth()->id(),
            'whatsapp_business_account_id' => $request->account_id
        ]);
        
        return response()->json([
            'success' => true,
            'label' => $label
        ]);
    }

    public function getConversationsByLabel(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:whatsapp_business_accounts,id',
            'label_id' => 'nullable|exists:whatsapp_labels,id',
            'filter' => 'nullable|in:all,unread'
        ]);

        $query = WhatsAppConversation::where('whatsapp_business_account_id', $request->account_id)
            ->with('latestMessage')
            ->withCount(['messages as unread_count' => function($query) {
                $query->where('read', false)->where('direction', 'in');
            }]);

        if ($request->label_id) {
            $query->whereHas('labels', function($query) use ($request) {
                $query->where('whatsapp_labels.id', $request->label_id);
            });
        }

        if ($request->filter === 'unread') {
            $query->whereHas('messages', function($q) {
                $q->where('read', false)->where('direction', 'in');
            });
        }

        $conversations = $query->get()
            ->sortByDesc(function($conversation) {
                return optional($conversation->latestMessage)->created_at;
            })
            ->values();

        $unreadConversationsCount = $conversations->filter(function($conversation) {
            return $conversation->unread_count > 0;
        })->count();

        $labels = WhatsappLabel::where('whatsapp_business_account_id', $request->account_id)
            ->withCount('conversations')
            ->get();

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
            'labels' => $labels,
            'unread_conversations_count' => $unreadConversationsCount
        ]);
    }
    
    public function assignLabels(Request $request, $conversationId)
    {
        $request->validate([
            'label_ids' => 'required|array',
            'label_ids.*' => 'exists:whatsapp_labels,id'
        ]);
        
        $conversation = WhatsAppConversation::findOrFail($conversationId);
        
        // if (!auth()->user()->whatsappBusinessAccounts->contains($conversation->whatsapp_business_account_id)) {
        //     abort(403);
        // }
        
        $conversation->labels()->sync($request->label_ids);
        
        return response()->json(['success' => true]);
    }
    
    public function deleteLabel($id)
    {
        $label = WhatsappLabel::findOrFail($id);
        
        // if ($label->user_id != auth()->id()) {
        //     abort(403);
        // }
        
        $label->delete();
        
        return response()->json(['success' => true]);
    }

    public function storeLead(Request $request)
    {
        $leadData = $request->lead;
        $products = $request->products;

        $product = Product::find($leadData['id_product']);

        $date = new DateTime();
        $ne = substr(strtoupper(Auth::user()->name), 0, 1);
        $n = substr(strtoupper(Auth::user()->name), -1);
        $last = Lead::latest('id')->first();
        $kk = $last ? $last->id + 1 : 1;
        $n_lead = $ne . $n . '-' . str_pad($kk, 5, '0', STR_PAD_LEFT);

        $lead = Lead::create([
            'n_lead' => $n_lead,
            'id_user' => $product->id_user,
            'id_country' => Auth::user()->country_id,
            'name' => $leadData['name_customer'],
            'phone' => $leadData['mobile'],
            'phone2' => $leadData['mobile2'] ?? null,
            'lead_value' => $leadData['total'],
            'market' => $leadData['market'] ?? 'Whatsapp',
            'method_payment' => 'COD',
            'id_product' => $leadData['id_product'],
            'id_assigned' => Auth::id(),
            'id_city' => $leadData['id_city'],
            'address' => $leadData['address'],
            'created_at' => $date,
        ]);

        foreach($products as $p) {
            LeadProduct::create([
                'id_lead' => $lead->id,
                'id_product' => $p['id_product'],
                'quantity' => $p['quantity'],
                'lead_value' => $p['price'] * $p['quantity'],
                'date_delivered' => $p['date_delivered'] ?? null,
            ]);
        }

        return response()->json(['success' => true]);
    }


    /**
     * Helper methods 
    */
    private function formatWhatsAppNumber($phoneNumber)
    {
        $formatted = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        if (substr($formatted, 0, 1) !== '+') {
            $formatted = '+' . $formatted;
        }
        
        return $formatted;
    }
}
