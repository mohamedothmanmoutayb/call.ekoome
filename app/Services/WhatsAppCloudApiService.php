<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WhatsAppCloudApiService

{
    protected $baseUrl = 'https://graph.facebook.com/v23.0/';
    protected $accessToken;
    protected $phoneNumberId;
    protected $businessAccountId;

    public function __construct($phoneNumberId = null)
    {
        $this->accessToken = env('WHATSAPP_ACCESS_TOKEN');
        $this->phoneNumberId = $phoneNumberId;
    }
    
    public function setCredentials($phoneNumberId, $accessToken)
    {
        $this->phoneNumberId = $phoneNumberId;
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Set the access token for API requests
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Set the business account ID for API requests
     */
    public function setBusinessAccountId($businessAccountId)
    {
        $this->businessAccountId = $businessAccountId;
        return $this;
    }

    /**
     * Exchange authorization code for access token
     */
    public function exchangeAuthCode($authCode, $clientId, $clientSecret, $redirectUri)
    {
        $response = Http::post($this->baseUrl . 'oauth/access_token', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'code' => $authCode
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Get business account details
     */
    public function getBusinessAccountDetails()
    {
        $response = Http::withToken($this->accessToken)
            ->get($this->baseUrl . $this->businessAccountId);

        return $this->handleResponse($response);
    }

    /**
     * Get phone numbers for business account
     */
    public function getPhoneNumbers()
    {
        $response = Http::withToken($this->accessToken)
            ->get($this->baseUrl . $this->businessAccountId . '/phone_numbers');

        return $this->handleResponse($response);
    }

    /**
     * Get phone number details
     */
    public function getPhoneNumberDetails($phoneNumberId)
    {
        $response = Http::withToken($this->accessToken)
            ->get($this->baseUrl . $phoneNumberId);

        return $this->handleResponse($response);
    }

    /**
     * Get business account ID from access token
     */
    public function getBusinessAccountIdFromToken()
    {
        $response = Http::withToken($this->accessToken)
            ->get($this->baseUrl . 'me/accounts');

        $data = $this->handleResponse($response);
        return $data['data'][0]['id'] ?? null;
    }

    /**
     * Handle API response
     */
    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        $error = $response->json()['error'] ?? [];
        $message = $error['message'] ?? 'WhatsApp API request failed';
        $code = $error['code'] ?? $response->status();

        Log::error('WhatsApp API Error', [
            'status' => $response->status(),
            'error' => $error
        ]);

        throw new \Exception($message, $code);
    }

    /**
     * Set the phone number ID for the API instance
     */
    public function setPhoneNumberId($phoneNumberId)
    {
        $this->phoneNumberId = $phoneNumberId;
        return $this;
    }

    /**
     * Send a text message
     */
    public function sendTextMessage($phoneNumberId,$to, $message, $previewUrl = false)
    {
        $response = Http::withToken($this->accessToken)
            ->post($this->baseUrl . $phoneNumberId . '/messages', [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => $previewUrl,
                    'body' => $message
                ]
            ]);

        return $this->handleResponse($response);
    }


    /**
         * Send a media message
     */
    public function sendMediaMessage($phoneNumberId, $to, $mediaPath,$mediaId, $mimeType, $caption = '')
    {
        $url = $this->baseUrl . $phoneNumberId . '/messages';
        
        $whatsappType = $this->getWhatsAppTypeFromMimeType($mimeType);
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => $whatsappType, 
        ];
        
        switch ($whatsappType) {
            case 'image':
                $payload['image'] = [
                    'id' => $mediaId,
                    'caption' => $caption
                ];
                break;
                
            case 'video':
                $payload['video'] = [
                    'id' => $mediaId,
                    'caption' => $caption
                ];
                break;
                
            case 'audio':
                $payload['audio'] = [
                    'id' => $mediaId,
                ];
                break;
                
            case 'document':
                $payload['document'] = [
                    'id' => $mediaId,
                    'caption' => $caption,
                    'filename' => basename($mediaPath)
                ];
                break;
                
            case 'sticker':
                $payload['sticker'] = [
                    'id' => $mediaId,
                ];
                break;
                
            default:
                throw new \Exception("Unsupported media type: {$mimeType}");
        }
        
        $response = Http::withToken($this->accessToken)
            ->post($url, $payload);
        
        return $this->handleResponse($response);
    }

    /**
     * Map MIME types to WhatsApp message types
     */
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

    /**
     * Send a template message
     */
    public function sendTemplateMessage($to, $templateName, $languageCode, $components = [])
    {
        $response = Http::withToken($this->accessToken)
            ->post($this->baseUrl . $this->phoneNumberId . '/messages', [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $languageCode
                    ],
                    'components' => $components
                ]
            ]);

        return $this->handleResponse($response);
    }

    /**
     * Register a message template
     */
    public function registerTemplate($businessAccountId, $templateData)
    {
        $response = Http::withToken($this->accessToken)
            ->post($this->baseUrl . $businessAccountId . '/message_templates', $templateData);

        return $this->handleResponse($response);
    }

    /**
     * Get all registered templates
     */
    public function getTemplates($businessAccountId)
    {
        $response = Http::withToken($this->accessToken)
            ->get($this->baseUrl . $businessAccountId . '/message_templates');

        return $this->handleResponse($response);
    }

    /**
     * Delete a template
     */
    public function deleteTemplate($businessAccountId, $templateName)
    {
        $response = Http::withToken($this->accessToken)
            ->delete($this->baseUrl . $businessAccountId . '/message_templates', [
                'name' => $templateName
            ]);

        return $this->handleResponse($response);
    }


     public function sendMessage($phoneNumberId, $accessToken, $to, $message, $media = null, $mimeType = null, $quotedMessageId = null)
    {
        $this->setCredentials($phoneNumberId, $accessToken);
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $this->formatPhoneNumber($to),
        ];

        if ($media) {
            // First upload media to WhatsApp servers
            $mediaId = $this->uploadMedia($media, $mimeType);
            
            if (!$mediaId) {
                return [
                    'error' => true,
                    'message' => 'Failed to upload media'
                ];
            }

            if (str_starts_with($mimeType, 'audio/')) {
                $payload['type'] = 'audio';
                $payload['audio'] = ['id' => $mediaId];
            } 
            elseif (str_starts_with($mimeType, 'image/')) {
                $payload['type'] = 'image';
                $payload['image'] = [
                    'id' => $mediaId,
                    'caption' => $message
                ];
            }
            elseif (str_starts_with($mimeType, 'video/')) {
                $payload['type'] = 'video';
                $payload['video'] = [
                    'id' => $mediaId,
                    'caption' => $message
                ];
            }
            else {
                $payload['type'] = 'document';
                $payload['document'] = [
                    'id' => $mediaId,
                    'caption' => $message,
                    'filename' => basename($media)
                ];
            }
        } else {
            $payload['type'] = 'text';
            $payload['text'] = ['body' => $message];
        }

        if ($quotedMessageId) {
            $payload['context'] = ['message_id' => $quotedMessageId];
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->post($this->baseUrl . $this->phoneNumberId . '/messages', $payload);

            $result = $response->json();
            
            if (isset($result['error'])) {
                Log::error('WhatsApp API Error: ' . json_encode($result['error']));
                return [
                    'error' => true,
                    'message' => $result['error']['message'] ?? 'Failed to send message'
                ];
            }

            return [
                'id' => $result['messages'][0]['id'] ?? Str::random(20),
                'status' => 'sent'
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Failed to send message',
                'exception' => $e->getMessage()
            ];
        }
    }

    private function uploadMedia($mediaPath, $mimeType)
    {
        try {
            $fileContents = Storage::disk('public')->get($mediaPath);
            
            $response = Http::withToken($this->accessToken)
                ->attach('file', $fileContents, basename($mediaPath), ['Content-Type' => $mimeType])
                ->post($this->baseUrl . $this->phoneNumberId . '/media');
            
            $result = $response->json();
            
            return $result['id'] ?? null;
            
        } catch (\Exception $e) {
            Log::error('Media upload failed: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteMessage($phoneNumberId, $accessToken, $messageId)
    {

        try {
            $url = "https://graph.facebook.com/v23.0/{$phoneNumberId}/messages";
            
            $response = Http::withToken($accessToken)
                ->delete($url, [
                    'message_id' => $messageId,
                    'messaging_product' => 'whatsapp'
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                return [
                    'status' => 'success',
                    'message' => 'Message deleted successfully',
                    'data' => $responseData
                ];
            } else {
                $errorResponse = $response->json();
                Log::error('WhatsApp API delete error: ' . json_encode($errorResponse));
                
                return [
                    'status' => 'error',
                    'message' => 'Failed to delete message via WhatsApp API',
                    'error' => $errorResponse['error']['message'] ?? 'Unknown error'
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Delete message error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to delete message',
                'error' => $e->getMessage()
            ];
        }
    }

       
    
    /**
     * Block WhatsApp user numbers
     */
    public function blockUsers($phoneNumberId, array $users)
    {
        $url = $this->baseUrl . $phoneNumberId . '/block_users';
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'block_users' => array_map(function($user) {
                return ['user' => $user];
            }, $users)
        ];

        $response = Http::withToken($this->accessToken)
            ->post($url, $payload);

        return $this->handleResponse($response);
    }


    /**
     * Unblock WhatsApp user numbers
     */
    public function unblockUsers($phoneNumberId, array $users)
    {
        $url = $this->baseUrl . $phoneNumberId . '/block_users';
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'block_users' => array_map(function($user) {
                return ['user' => $user];
            }, $users)
        ];

        $response = Http::withToken($this->accessToken)
            ->delete($url, $payload);

        return $this->handleResponse($response);
    }

    /**
     * Get list of blocked WhatsApp user numbers
     */
    public function getBlockedUsers($phoneNumberId, $limit = 10, $after = null, $before = null)
    {
        $url = $this->baseUrl . $phoneNumberId . '/block_users';
        
        $queryParams = ['limit' => $limit];
        
        if ($after) {
            $queryParams['after'] = $after;
        }
        
        if ($before) {
            $queryParams['before'] = $before;
        }

        $response = Http::withToken($this->accessToken)
            ->get($url, $queryParams);

        return $this->handleResponse($response);
    }

    /**
     * Check if a specific user is blocked
     */
    public function isUserBlocked($phoneNumberId, $userNumber)
    {
        try {
            $blockedUsers = $this->getBlockedUsers($phoneNumberId, 1000); 
            
            foreach ($blockedUsers['data'][0]['block_users'] ?? [] as $blockedUser) {
                if ($blockedUser['wa_id'] === $userNumber || $blockedUser['input'] === $userNumber) {
                    return true;
                }
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error checking blocked user status: ' . $e->getMessage());
            return false;
        }
    }

    private function formatWhatsAppNumber($phoneNumber)
    {
        $formatted = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        if (substr($formatted, 0, 1) !== '+') {
            $formatted = '+' . $formatted;
        }
        
        return $formatted;
    }

}