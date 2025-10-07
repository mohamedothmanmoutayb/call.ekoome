    @extends('backend.layouts.app')

    @section('css')
        <style>
            .whatsapp-container {
                position: relative;
                max-width: 100%;
                height: calc(100vh - 40px);
                background: #fff;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15), 0 -8px 20px rgba(0, 0, 0, 0.1), 8px 0 20px rgba(0, 0, 0, 0.1), -8px 0 20px rgba(0, 0, 0, 0.1);
                display: flex;
                overflow: hidden;
            }

            .leftSide {
                position: relative;
                flex: 30%;
                background: #fff;
                border-right: 1px solid rgba(0, 0, 0, 0.2);
                display: flex;
                flex-direction: column;
                height: 100%;
                width: 200px;
            }

            .rightSide {
                position: relative;
                flex: 70%;
                background: #EFF6FF;
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            .rightSide::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.06;
            }

            .header {
                position: relative;
                z-index: 1;
                width: 100%;
                height: 60px;
                background: #FFF;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 15px;
                flex-shrink: 0;
            }

            .imgText {
                display: flex;
                gap: 21px;
                align-items: center;
            }

            .header h4 {
                font-size: 1.2em;
                color: #111;
                font-weight: 600;
            }

            .userimg {
                position: relative;
                width: 40px;
                height: 40px;
                overflow: hidden;
                border-radius: 50%;
                cursor: pointer;
            }

            .cover {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .nav_icons {
                display: flex;
            }

            .nav_icons>li {
                display: flex;
                list-style: none;
                cursor: pointer;
                color: #51585c;
                font-size: 1.5em;
                margin-left: 22px;
            }

            .search_chat {
                position: relative;
                width: 100%;
                height: 50px;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 0 15px;
                flex-shrink: 0;
            }

            .loading-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 90vw;
                margin-left: calc(-45vw + 50%);
                height: 100%;
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(3px);
                z-index: 1000;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }

            .loading-overlay.hidden {
                display: none;
            }

            .search_chat div {
                width: 100%;
            }

            .search_chat div input {
                width: 100%;
                outline: none;
                border: none;
                background: #EFF6FF !important;
                padding: 6px;
                height: 38px;
                border-radius: 30px;
                font-size: 14px;
                padding-left: 40px;
            }

            .search_chat div input::placeholder {
                color: #bbb;
            }

            .search_chat div ion-icon {
                position: absolute;
                left: 30px;
                top: 14px;
                font-size: 1.2em;
            }

            .chatlist {
                position: relative;
                flex: 1;
                overflow-y: auto;
                height: 100%;
            }

            .chatlist .block {
                position: relative;
                width: 100%;
                display: flex;
                align-items: center;
                padding: 15px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.06);
                cursor: pointer;
            }

            .chatlist .block.active {
                background: #ebebeb;
            }

            .chatlist .block:hover {
                background: #f5f5f5;
            }

            .chatlist .block.unread .details .listHead .time {
                color: #06d755;
            }

            .chatlist .block .imgBox {
                position: relative;
                min-width: 45px;
                height: 45px;
                overflow: hidden;
                border-radius: 50%;
                margin-right: 10px;
            }

            .chatlist .block .details {
                position: relative;
                width: 100%;
            }

            .chatlist .block .details .listHead {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
            }

            .chatlist .block .details .listHead h4 {
                font-size: 1.1em;
                font-weight: 600;
                color: #111;
            }

            .chatlist .block .details .listHead .time {
                font-size: 0.75em;
                color: #aaa;
            }

            .message_p {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .message_p b {
                background: #06d755;
                color: #fff;
                min-width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 0.75rem;
            }

            .message_p p {
                color: #aaa;
                display: -webkit-box;
                -webkit-line-clamp: 1;
                font-size: 0.9em;
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .chatbox {
                position: relative;
                width: 100%;
                flex: 1;
                padding: 20px 50px;
                height: calc(100% - 160px);
                overflow-y: auto;
            }

            .message {
                position: relative;
                display: flex;
                width: 100%;
                margin: 5px 0;
            }

            .message p {
                position: relative;
                right: 0;
                text-align: right;
                /* max-width: 65%; */
                padding: 12px;
                background: #C3F5D2;
                border-radius: 10px;
                font-size: 0.9em;
                color: #585252;
            }

            .message p::before {
                content: "";
                position: absolute;
                top: 0;
                right: -12px;
                width: 20px;
                height: 20px;
                background: linear-gradient(135deg, #C3F5D2 0%, #C3F5D2 50%, transparent 50%, transparent);
            }

            .message p span {
                display: block;
                margin-top: 5px;
                font-size: 0.9em;
                /* opacity: 0.8; */
                display: flex !important;
                align-content: center !important;
                color: #585252;
            }

            .my_msg {
                justify-content: flex-end;
            }

            .friend_msg {
                justify-content: flex-start;
            }

            .friend_msg p {
                background: #fff;
                text-align: left;
            }

            .message.friend_msg p::before {
                content: "";
                position: absolute;
                top: 0;
                left: -12px;
                width: 20px;
                height: 20px;
                background: linear-gradient(225deg, #fff 0%, #fff 50%, transparent 50%, transparent);
            }

            .media-message {
                position: relative;
                max-width: 65%;
                margin-bottom: 2rem;
            }

            .media-message img,
            .media-message video {
                max-width: 300px;
                border-radius: 8px;
                display: block;
            }

            .media-message .timestamp {
                position: absolute;
                bottom: 8px;
                right: 8px;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 0.75em;
            }

            .audio-player {
                width: 250px;
                height: 50px;
                background: #dcf8c6;
                border-radius: 50px;
                display: flex;
                align-items: center;
                padding: 0 15px;
                position: relative;
            }

            .friend_msg .audio-player {
                background: #fff;
            }

            .audio-player .play-btn {
                width: 30px;
                height: 30px;
                background: #25D366;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                color: white;
                cursor: pointer;
                margin-right: 15px;
                flex-shrink: 0;
            }

            .audio-player .progress-container {
                flex-grow: 1;
                height: 4px;
                background: rgba(0, 0, 0, 0.1);
                border-radius: 2px;
                margin-right: 10px;
                position: relative;
                cursor: pointer;
            }

            .audio-player .progress-bar {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background: #25D366;
                border-radius: 2px;
                width: 0%;
            }

            .audio-player .time {
                font-size: 12px;
                color: #555;
                white-space: nowrap;
                min-width: 40px;
                text-align: right;
            }

            .audio-player .timestamp {
                position: absolute;
                bottom: -25px;
                right: 8px;
                background: rgba(0, 0, 0, 0.5);
                color: white;
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 0.75em;
            }

            audio {
                display: none;
            }

            .empty-chat-state {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }

            .empty-chat-state .content {
                text-align: center;
            }

            .empty-chat-state img {
                width: 200px;
                opacity: 0.5;
            }

            .chat_input {
                position: relative;
                width: 100%;
                min-height: 60px;
                background: white;
                padding: 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-shrink: 0;
            }

            .input-container {
                display: flex;
                align-items: center;
                width: 100%;
                gap: 10px;
            }

            .message-input-wrapper {
                flex-grow: 1;
                position: relative;
            }

            .text-input {
                width: 100%;
                padding: 10px 20px;
                border: none;
                outline: none;
                border-radius: 30px;
                font-size: 1em;
                background: #EFF6FF;
                transition: all 0.3s ease;
            }

            .audio-recording-ui {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                padding: 8px 15px;
                background: #fff;
                border-radius: 30px;
            }

            .recording-indicator {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .pulse-animation {
                width: 12px;
                height: 12px;
                background: #f44336;
                border-radius: 50%;
                animation: pulse 1.5s infinite;
            }

            @keyframes pulse {
                0% {
                    transform: scale(0.95);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.1);
                    opacity: 0.8;
                }

                100% {
                    transform: scale(0.95);
                    opacity: 1;
                }
            }

            .recording-indicator span {
                font-size: 0.9em;
                color: #f44336;
            }

            .recording-timer {
                font-size: 0.9em;
                color: #555;
                margin: 0 10px;
            }

            .cancel-recording-btn {
                background: none;
                border: none;
                color: #555;
                cursor: pointer;
                font-size: 1.2em;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-icon {
                background: none;
                border: none;
                color: #51585c;
                font-size: 1.8em;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 5px;
            }

            .send-btn {
                color: #25D366;
            }

            .record-btn {
                color: #f44336;
            }

            .record-btn.hidden {
                display: none !important;
            }

            .send-btn.hidden {
                display: none !important;
            }

            .loading-dots {
                display: inline-flex;
                align-items: flex-end;
                height: 20px;
                margin-bottom: 11px;
            }

            .loading-dots span {
                display: inline-block;
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #25D366;
                margin: 0 1px;
                animation: bounce 1.4s infinite ease-in-out both;
            }

            .loading-dots span:nth-child(1) {
                animation-delay: -0.32s;
            }

            .loading-dots span:nth-child(2) {
                animation-delay: -0.16s;
            }

            @keyframes bounce {

                0%,
                80%,
                100% {
                    transform: scale(0);
                }

                40% {
                    transform: scale(1.0);
                }
            }

            .new-chat-container {
                padding: 10px;
                border-bottom: 1px solid #eee;
            }

            #newChatBtn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            #newChatBtn ion-icon {
                font-size: 1.2em;
            }

            .dropdown {
                position: relative;
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-item {
                display: block;
                padding: 8px 20px;
                clear: both;
                font-weight: 400;
                color: #333;
                text-decoration: none;
                white-space: nowrap;
                background-color: transparent;
                border: 0;
            }

            .dropdown-item:hover {
                background-color: #f5f5f5;
            }

            .dropdown-divider {
                height: 1px;
                margin: 5px 0;
                overflow: hidden;
                background-color: #e5e5e5;
            }

            .date-separator {
                position: relative;
                display: flex;
                justify-content: center;
                margin: 15px 0;
                width: 100%;
            }

            .date-separator span {
                background: rgba(225, 245, 254, 0.92);
                color: #607d8b;
                font-size: 12px;
                padding: 4px 12px;
                border-radius: 20px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
                z-index: 2;
            }

            .sticky-date-header {
                position: sticky;
                top: 60px;
                background: #f2f2f2;
                z-index: 10;
                padding: 5px 0;
                text-align: center;
                font-size: 12px;
                color: #607d8b;
                box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
            }

            .reply-container {
                display: flex;
                margin-bottom: 8px;
                cursor: pointer;
                background: rgba(0, 0, 0, 0.05);
                border-radius: 8px;
                padding: 8px;
                max-width: 80%;
            }

            .reply-line {
                width: 3px;
                background: #25D366;
                border-radius: 3px;
                margin-right: 8px;
            }

            .reply-content {
                flex: 1;
                overflow: hidden;
            }

            .reply-sender {
                font-weight: bold;
                font-size: 0.8em;
                color: #25D366;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .reply-text {
                font-size: 0.9em;
                color: #555;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .message-content {
                position: relative;
            }

            .my_msg .reply-container {
                background: rgba(0, 0, 0, 0.03);
            }

            .my_msg .reply-line {
                background: #075E54;
            }

            .my_msg .reply-sender {
                color: #075E54;
            }

            .message-status {
                display: inline-flex;
                align-items: center;
                margin-left: 5px;
                vertical-align: middle;
            }

            .message-status .icon {
                font-size: 14px;
                color: #999;
            }

            .message-status .icon.read {
                color: #3955ec;
            }

            .audio-message-played {
                color: #3955ec;
            }

            .message:hover .message-actions {
                opacity: 1;
            }

            .message-actions {
                position: absolute;
                right: -30px;
                top: 43%;
                transform: translateY(-50%);
                opacity: 0;
                transition: opacity 0.2s ease;
                display: flex;
                background: #fff;
                border-radius: 18px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                z-index: 1;
            }

            .message-actions-btn {
                background: none;
                border: none;
                color: #667781;
                font-size: 1em;
                padding: 6px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .message-actions-btn:hover {
                color: #111;
            }

            .message-actions-menu {
                position: absolute;
                right: 0;
                bottom: 100%;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                min-width: 160px;
                z-index: 10;
                display: none;
            }

            .message-actions-menu.show {
                display: block;
            }

            .message-actions-menu li {
                padding: 8px 16px;
                cursor: pointer;
                list-style: none;
                font-size: 0.9em;
                color: #111;
            }

            .message-actions-menu li:hover {
                background: #f5f5f5;
            }

            .message-actions-menu li.delete {
                color: #f44336;
            }

            .deleted-message p {
                color: gray;
                font-style: italic;
            }

            .block .message_p p:contains("This message has been deleted") {
                color: gray;
                font-style: italic;
            }

            /* Labels modal styles */
            .label-filter-container {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                padding: 15px;
                z-index: 10;
                position: relative;
            }

            .label-filter-badge {
                display: flex;
                align-items: center;
                padding: 5px 10px;
                border-radius: 20px;
                background-color: #f0f0f0;
                cursor: pointer;
                transition: all 0.2s;
                font-size: 0.85rem;
            }

            .label-filter-badge:hover {
                opacity: 0.8;
            }

            .label-filter-badge.active {
                background-color: #e3f2fd;
                font-weight: bold;
            }

            .label-color-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin-right: 6px;
            }

            .label-count {
                margin-left: 5px;
                font-size: 0.75rem;
                color: #666;
            }

            /* Labels modal styles */
            .label-card {
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 15px;
                transition: all 0.2s;
                position: relative;
            }

            .label-card:hover {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }

            .label-color {
                width: 20px;
                height: 20px;
                border-radius: 4px;
                display: inline-block;
                margin-right: 10px;
            }

            .label-checkbox {
                width: 20px;
                height: 20px;
            }

            .label-actions {
                position: absolute;
                bottom: 10px;
                right: 10px;
            }

            #labelsContainer {
                max-height: 400px;
                overflow-y: auto;
                padding-right: 10px;
            }

            .colorpick-eyedropper-input-trigger {
                display: none !important;
            }

            .form-check-labels {
                display: flex;
                min-height: 1.313em;
                padding-left: 1.813em;
                margin-bottom: .125rem;
                align-items: center;
                justify-content: space-between;
            }

            /* Upload Progress Bar Styles */
            #uploadProgressBar {
                display: none;
                position: sticky;
                top: 0;
                width: 100%;
                height: 3px;
                background: #f0f0f0;
                z-index: 9999;
            }

            #uploadProgressBar div {
                height: 100%;
                background: #25D366;
                width: 0%;
                transition: width 0.3s;
            }

            /* Indeterminate progress animation */
            @keyframes progressIndeterminate {
                0% {
                    transform: translateX(-100%);
                }

                100% {
                    transform: translateX(330%);
                }
            }

            #uploadProgressIndicator {
                display: none;
                position: sticky;
                top: 0;
                width: 100%;
                height: 3px;
                background: #f0f0f0;
                z-index: 9999;
            }

            #uploadProgressIndicator div {
                height: 100%;
                background: #25D366;
                width: 30%;
                animation: progressIndeterminate 1.5s infinite;
            }

            /* Template Selector Styles */
            .templates-container {
                max-height: calc(100vh - 200px);
                overflow-y: auto;
            }

            .template-card {
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 12px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .template-card:hover {
                background-color: #f8f9fa;
                border-color: #c6c6c6;
            }

            .template-card.selected {
                border-color: #25D366;
                background-color: rgba(37, 211, 102, 0.05);
            }

            .template-name {
                font-weight: 600;
                margin-bottom: 4px;
                color: #333;
            }

            .template-category {
                font-size: 0.85rem;
                color: #6c757d;
                margin-bottom: 6px;
            }

            .template-body-preview {
                font-size: 0.9rem;
                color: #495057;
                margin-bottom: 8px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .template-meta {
                display: flex;
                justify-content: space-between;
                font-size: 0.8rem;
                color: #6c757d;
            }

            /* Template Message Preview Styles */
            .template-message-preview {
                background: white;
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 13px;
                max-width: 300px;
            }

            .template-header {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
                padding-bottom: 8px;
                border-bottom: 1px solid #f0f0f0;
            }

            .template-badge {
                background: #25D366;
                color: white;
                padding: 2px 6px;
                border-radius: 4px;
                font-size: 10px;
                margin-right: 8px;
            }

            .template-name {
                font-weight: 600;
                font-size: 14px;
            }

            .template-header-media {
                margin-bottom: 10px;
            }

            .template-header-media img,
            .template-header-media video {
                width: 100%;
                border-radius: 4px;
            }

            .template-header-document {
                display: flex;
                align-items: center;
                padding: 8px;
                background: #f9f9f9;
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .document-icon {
                font-size: 24px;
                margin-right: 10px;
                color: #6c757d;
            }

            .document-info {
                flex: 1;
            }

            .document-name {
                font-weight: 500;
                font-size: 12px;
            }

            .document-size {
                font-size: 10px;
                color: #6c757d;
            }

            .template-header-text {
                font-weight: 600;
                margin-bottom: 10px;
                padding: 8px;
                background: #f0f8ff;
                border-radius: 4px;
            }

            .template-body {
                margin-bottom: 10px;
                line-height: 1.4;
            }

            .template-footer {
                font-size: 12px;
                color: #6c757d;
                margin-bottom: 10px;
                padding-top: 8px;
                border-top: 1px solid #f0f0f0;
            }

            .template-buttons {
                display: flex;
                flex-direction: column;
                gap: 6px;
                margin-bottom: 10px;
            }

            .template-button {
                padding: 8px 12px;
                border-radius: 20px;
                text-align: center;
                font-size: 12px;
                cursor: pointer;
                border: 1px solid #e0e0e0;
            }

            .template-button.url-button {
                border-color: #007bff;
                color: #007bff;
            }

            .template-button.phone-button {
                border-color: #25D366;
                color: #25D366;
            }

            .template-button.quick-reply-button {
                border-color: #6c757d;
                color: #6c757d;
            }

            .template-button.copy-code-button {
                border-color: #ffc107;
                color: #ffc107;
                font-weight: bold;
            }

            .template-limited-offer {
                margin-bottom: 10px;
            }

            .offer-badge {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 6px 10px;
                background: linear-gradient(135deg, #ff6b6b, #ee5a24);
                color: white;
                border-radius: 6px;
                font-size: 11px;
            }

            .offer-expiry {
                margin-left: auto;
                font-size: 10px;
                opacity: 0.9;
            }

            .template-timestamp {
                text-align: right;
                font-size: 10px;
                color: #999;
            }

            /* WhatsApp Preview Styles (enhanced) */
            .preview-container {
                background: #E5DDD5;
                border-radius: 16px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            }

            .preview-header {
                background: #075E54;
                padding: 15px 20px;
                color: white;
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .preview-header .avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #25D366;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }

            .preview-header .name {
                font-weight: 500;
                font-size: 16px;
            }

            .preview-body {
                padding: 25px;
                display: flex;
                flex-direction: column;
                gap: 15px;
                background: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png') center;
                background-size: 500px;
                min-height: 200px;
            }

            .preview-message {
                max-width: 88%;
                padding: 12px 16px;
                border-radius: 8px;
                position: relative;
                font-size: 14px;
                line-height: 1.4;
            }

            .preview-message.incoming {
                background: white;
                align-self: flex-start;
                border-top-left-radius: 0;
            }

            .preview-message.outgoing {
                background: #DCF8C6;
                align-self: flex-end;
                border-top-right-radius: 0;
            }

            .preview-message .header {
                font-weight: 600;
                margin-bottom: 8px;
                color: #075E54;
                font-size: 15px;
            }

            .preview-message .content {
                line-height: 1.5;
            }

            .preview-message .footer {
                margin-top: 10px;
                font-size: 0.75rem;
                color: #667781;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                padding-top: 8px;
            }

            .preview-message .buttons {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-top: 12px;
                width: 100%;
            }

            .preview-message .button {
                width: 100%;
                text-align: center;
                padding: 8px 12px;
                border-radius: 20px;
                background: white;
                border: 1px solid #E6E6E6;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .preview-message .button:hover {
                background: #f0f0f0;
            }

            /* Media upload styles */
            .media-preview-container {
                border: 2px dashed #ddd;
                border-radius: 12px;
                padding: 20px;
                background: #f9f9f9;
            }

            .media-preview {
                text-align: center;
                min-height: 150px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .media-preview img {
                max-width: 100%;
                max-height: 200px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .media-preview video {
                max-width: 100%;
                max-height: 200px;
                border-radius: 8px;
            }

            .document-preview {
                padding: 20px;
                background: white;
                border-radius: 8px;
                text-align: center;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .document-preview ion-icon {
                font-size: 3rem;
                color: #6c757d;
            }

            .media-required-badge {
                position: absolute;
                top: 10px;
                right: 10px;
                background: #dc3545;
                color: white;
                padding: 2px 8px;
                border-radius: 4px;
                font-size: 0.75rem;
            }

            /* Variable input styles */
            .variable-input-group {
                margin-bottom: 15px;
            }

            .variable-label {
                font-weight: 500;
                margin-bottom: 5px;
                display: block;
            }

            .variable-helper {
                font-size: 0.8rem;
                color: #6c757d;
                margin-top: 4px;
            }

            /* Buttons styles */
            .limited-time-offer {
                margin: 10px 0;
                padding: 8px 12px;
                background: linear-gradient(135deg, #ff6b6b, #ee5a24);
                border-radius: 8px;
                color: white;
                text-align: center;
            }

            .offer-badge {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                font-weight: 600;
            }

            .buttons {
                display: flex;
                flex-direction: column;
                gap: 8px;
                margin-top: 12px;
            }

            .button {
                width: 100%;
                text-align: center;
                padding: 8px 12px;
                border-radius: 20px;
                background: white;
                border: 1px solid #E6E6E6;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .button:hover {
                background: #f0f0f0;
            }

            .url-button {
                border-color: #007bff;
                color: #007bff;
            }

            .copy-code-button {
                border-color: #ffc107;
                color: #ffc107;
                font-weight: bold;
            }

            .phone-button {
                border-color: #25D366;
                color: #25D366;
            }

            .quick-reply-button {
                border-color: #6c757d;
                color: #6c757d;
            }

            /* New right sidebar for contact details */
            .contactSidebar {
                width: 582px;
                background: white;
                display: flex;
                flex-direction: column;
                overflow-y: auto;
            }

            .contact-header {
                margin-bottom: 20px;
                margin-top: 12px;
                display: flex;
            }

            .contact-avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                margin: 0 15px;
                overflow: hidden;
                border: 3px solid #f5f5f5;
            }

            .contact-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }


            .contact-name {
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 5px;
            }

            .section-title {
                font-size: 16px;
                font-weight: 700;
                margin-bottom: 12px;
            }

            .detail-item {
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .detail-label {
                font-size: 15px;
                color: #999;
            }

            .detail-value {
                font-size: 16px;
                font-weight: 500;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .detail-value.adresse {
                width: 266px;

            }

            .blurred {
                color: transparent;
                text-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
            }

            .tags-section {
                margin: 20px 0;
                padding: 15px;
            }

            .tag {
                display: inline-flex;
                align-items: center;
                padding: 5px 10px;
                color: white;
                border-radius: 15px;
                font-size: 12px;
                margin-right: 5px;
            }

            .contact-actions {
                display: flex;
                gap: 5px;
            }

            .contact-actions .btn {
                padding: 5px 8px;
                border-radius: 50%;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .contact-details-section,
            .assigned-agent-section,
            .order-history-section {
                padding: 15px;
                border-radius: 8px;
                margin-top: 15px;
            }

            /* Agent assignment styles */
            .agent-group {
                margin-bottom: 1.5rem;
            }

            .agent-group-header {
                font-weight: 600;
                color: #6c757d;
                margin-bottom: 0.5rem;
                padding-bottom: 0.25rem;
                border-bottom: 1px solid #dee2e6;
            }

            .agent-item {
                display: flex;
                align-items: center;
                padding: 0.75rem;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                margin-bottom: 0.5rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            .agent-item:hover {
                background-color: #f8f9fa;
                border-color: #007bff;
            }

            .agent-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                overflow: hidden;
                margin-right: 0.75rem;
            }

            .agent-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .agent-info {
                flex: 1;
            }

            .agent-name {
                font-weight: 600;
                margin-bottom: 0.1rem;
            }

            .agent-role {
                font-size: 0.875rem;
                color: #6c757d;
                margin-bottom: 0.25rem;
            }

            .agent-workload {
                font-size: 0.75rem;
            }

            .workload-badge {
                padding: 0.15rem 0.4rem;
                border-radius: 0.25rem;
                font-size: 0.7rem;
                font-weight: 600;
            }

            .workload-low {
                background-color: #d4edda;
                color: #155724;
            }

            .workload-medium {
                background-color: #fff3cd;
                color: #856404;
            }

            .workload-high {
                background-color: #f8d7da;
                color: #721c24;
            }

            .workload-urgent {
                background-color: #721c24;
                color: white;
            }

            .agent-stats {
                display: flex;
                gap: 0.5rem;
            }

            .stat {
                text-align: center;
            }

            .stat-label {
                display: block;
                font-size: 0.7rem;
                color: #6c757d;
            }

            .stat-value {
                display: block;
                font-weight: 600;
                font-size: 0.9rem;
            }

            /* Assignment history styles */
            .assignment-history-item {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                padding: 0.75rem;
                margin-bottom: 0.5rem;
            }

            .assignment-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .priority-badge {
                font-size: 0.75rem;
                padding: 0.15rem 0.4rem;
                border-radius: 0.25rem;
                font-weight: 600;
            }

            .assignment-details {
                font-size: 0.875rem;
            }

            .assignment-reason {
                background: #f8f9fa;
                padding: 0.5rem;
                border-radius: 0.25rem;
                margin: 0.5rem 0;
            }

            .resolved-date {
                color: #28a745;
                font-weight: 600;
            }

            /* Assignment info styles */
            .assignment-info {
                background: #f8f9fa;
                padding: 0.75rem;
                border-radius: 0.375rem;
                border-left: 4px solid #007bff;
                margin-top: 0.5rem;
            }

            .assigned-agent {
                display: flex;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .agent-avatar-small {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                overflow: hidden;
                margin-right: 0.5rem;
            }

            .agent-avatar-small img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .assignment-priority {
                font-size: 0.75rem;
                padding: 0.15rem 0.4rem;
                border-radius: 0.25rem;
                display: inline-block;
            }

            .priority-low {
                background-color: #d4edda;
                color: #155724;
            }

            .priority-medium {
                background-color: #fff3cd;
                color: #856404;
            }

            .priority-high {
                background-color: #f8d7da;
                color: #721c24;
            }

            .priority-urgent {
                background-color: #721c24;
                color: white;
            }

            .assignment-reason {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                padding: 0.5rem;
                background: white;
                border-radius: 0.25rem;
            }

            .assignment-meta {
                font-size: 0.75rem;
                color: #6c757d;
            }

            /* Assignment badges in conversation list */
            .assignment-badge {
                position: absolute;
                top: 5px;
                right: 5px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                color: white;
                font-weight: bold;
            }

            .assignment-badge.priority-low {
                background-color: #28a745;
            }

            .assignment-badge.priority-medium {
                background-color: #ffc107;
                color: #000;
            }

            .assignment-badge.priority-high {
                background-color: #fd7e14;
            }

            .assignment-badge.priority-urgent {
                background-color: #dc3545;
            }

            /* Order history styles */
            .order-item {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                padding: 0.75rem;
                margin-bottom: 0.5rem;
            }

            .order-header {
                display: flex;
                justify-content: between;
                align-items: center;
                margin-bottom: 0.5rem;
            }

            .order-date {
                font-weight: 600;
                color: #495057;
            }

            .order-status {
                font-size: 0.75rem;
                padding: 0.15rem 0.4rem;
                border-radius: 0.25rem;
                font-weight: 600;
            }

            .order-status.completed {
                background-color: #d4edda;
                color: #155724;
            }

            .order-status.pending {
                background-color: #fff3cd;
                color: #856404;
            }

            .order-status.cancelled {
                background-color: #f8d7da;
                color: #721c24;
            }

            .order-details {
                display: flex;
                justify-content: between;
                margin-bottom: 0.5rem;
            }

            .order-products {
                flex: 1;
                font-size: 0.875rem;
                color: #6c757d;
            }

            .order-amount {
                font-weight: 600;
                color: #28a745;
            }

            .order-actions {
                text-align: right;
            }

            .contact-top-bar {
                display: flex;
                gap: 6px;
                padding: 9px 11px;
                background: #EFF6FF;
                margin-bottom: 1px;
                justify-content: flex-end;
            }

            .contact-info {
                display: flex;
                align-items: self-start;
                justify-content: space-between;
                width: 71%;
            }

            .add-tag {
                background: #476bc2;
                color: white;
                padding: 10px 9px;
                font-size: 13px;
            }

            .add-tag:hover {
                background: #375aab;
            }

            .custom-select {
                border-radius: 12px;
                padding: 4px 8px;
                font-size: 14px;
            }

            .nav-link ion-icon {
                cursor: pointer;
                color: #555;
            }

            .dropdown-menu {
                font-size: 14px;
            }

            #accountFilter {
                border: 0;
                background: none;
                font-size: 17px;
            }

            .floating-labels .form-control {
                font-size: 15px;
            }

            .highlight {
                background-color: #C7D3E1;
                padding: 0 2px;
                border-radius: 3px;
            }

            /* Search Modal Styles */

            #searchMessagesModal .modal-header {
                padding: 15px;
                background: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
            }

            #searchMessagesModal .input-group {
                width: 100%;
            }

            #messageSearchInput {
                font-size: 16px;
                padding: 10px 0;
                background: transparent;
                box-shadow: none !important;
            }

            #messageSearchInput:focus {
                outline: none;
                box-shadow: none !important;
            }

            #voiceSearchBtn.recording {
                color: #dc3545 !important;
                animation: pulse 1.5s infinite;
            }

            #advancedSearchOptions {
                background: #f8f9fa;
            }

            .advanced-search-options .form-control,
            .advanced-search-options .form-select {
                font-size: 0.875rem;
            }

            .search-results-container {
                height: 250px;
                display: flex;
                flex-direction: column;
            }

            .search-info-bar {
                background: #f8f9fa;
                flex-shrink: 0;
            }

            .search-results-list {
                flex: 1;
                overflow-y: auto;
                padding: 0;
            }

            .search-result-item {
                padding: 15px;
                border-bottom: 1px solid #f0f0f0;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .search-result-item:hover {
                background-color: #f8f9fa;
            }

            .search-result-item.active {
                background-color: #e3f2fd;
                border-left: 4px solid #2196f3;
            }

            .search-result-message {
                margin-bottom: 5px;
                line-height: 1.4;
            }

            .search-result-meta {
                font-size: 12px;
                color: #666;
                display: flex;
                justify-content: space-between;
            }

            .search-highlight {
                background-color: #ffeb3b;
                padding: 2px 1px;
                border-radius: 2px;
                font-weight: 600;
            }

            .no-results {
                text-align: center;
                padding: 40px 20px;
                color: #666;
            }

            .search-loading {
                text-align: center;
                padding: 20px;
                color: #666;
            }

            .voice-search-indicator {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999999;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .voice-search-popup {
                background: white;
                padding: 30px;
                border-radius: 15px;
                text-align: center;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            }

            .voice-search-popup .pulse-animation {
                width: 80px;
                height: 80px;
                background: #dc3545;
                border-radius: 50%;
                margin: 0 auto 20px;
                animation: voicePulse 1.5s infinite;
            }

            @keyframes voicePulse {
                0% {
                    transform: scale(0.8);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.2);
                    opacity: 0.7;
                }

                100% {
                    transform: scale(0.8);
                    opacity: 1;
                }
            }

            .voice-search-text {
                font-size: 18px;
                font-weight: 600;
                color: #333;
            }

            .message-highlight {
                background-color: #fff3cd !important;
                border-left: 4px solid #ffc107 !important;
                padding: 8px 12px !important;
                border-radius: 4px;
                animation: pulseHighlight 2s ease-in-out;
            }

            @keyframes pulseHighlight {
                0% {
                    background-color: #fff3cd;
                }

                50% {
                    background-color: #ffeaa7;
                }

                100% {
                    background-color: #fff3cd;
                }
            }

            kbd {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 3px;
                padding: 2px 5px;
                font-size: 0.875em;
                color: #495057;
            }

            #searchMessagesModal {
                z-index: 99999;
            }

            .advanced-search-options {
                transition: all 0.3s ease;
            }

            .search-result-type {
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 10px;
                background: #e9ecef;
                color: #495057;
                margin-left: 8px;
            }

            .search-result-type.media {
                background: #d1ecf1;
                color: #0c5460;
            }

            .search-result-type.template {
                background: #d4edda;
                color: #155724;
            }

            .search-result-type.deleted {
                background: #f8d7da;
                color: #721c24;
            }

            @media (max-width: 768px) {
                #searchMessagesModal .modal-header .d-flex {
                    flex-direction: column;
                    gap: 10px;
                }

                #searchMessagesModal .input-group {
                    margin-bottom: 10px;
                }

                .advanced-search-options .row {
                    flex-direction: column;
                }

                .advanced-search-options .col-md-4,
                .advanced-search-options .col-md-3,
                .advanced-search-options .col-md-2 {
                    width: 100%;
                    margin-bottom: 10px;
                }
            }

            /* Enhanced Label Filters Container */
            .label-filters-container {
                padding: 10px 15px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.06);
                background: #fff;
            }

            /* Quick Filter Buttons */
            .no-conversations {
                text-align: center;
                padding: 40px 20px;
                color: #666;
                font-style: italic;
                background: #f8f9fa;
                border-radius: 8px;
                margin: 10px;
            }

            .quick-filter-buttons {
                display: flex;
                gap: 8px;
                margin-bottom: 12px;
            }

            .btn-filter {
                display: flex;
                align-items: center;
                padding: 6px 12px;
                border: 1px solid #e0e0e0;
                border-radius: 20px;
                background: #f8f9fa;
                color: #495057;
                font-size: 0.8rem;
                font-weight: 500;
                transition: all 0.3s ease;
                position: relative;
            }

            .btn-filter:hover {
                background: #e9ecef;
                border-color: #ced4da;
            }

            .btn-filter.active {
                background: #075E54;
                border-color: #075E54;
                color: white;
            }

            .btn-filter ion-icon {
                font-size: 14px;
            }

            .unread-badge {
                position: absolute;
                top: -6px;
                right: -6px;
                background: #dc3545;
                color: white;
                border-radius: 10px;
                min-width: 18px;
                height: 18px;
                font-size: 0.7rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
            }

            /* Label Filters Carousel */
            .label-filters-carousel {
                display: flex;
                align-items: center;
                gap: 8px;
                position: relative;
            }

            .label-filters-wrapper {
                overflow: hidden;
                width: 100%;
                position: relative;
            }

            .label-filters-track {
                display: flex;
                gap: 8px;
                transition: transform 0.3s ease;
                width: max-content;
            }

            .carousel-control {
                background: #f8f9fa;
                border: 1px solid #e0e0e0;
                border-radius: 50%;
                width: 28px;
                height: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #495057;
                transition: all 0.3s ease;
                flex-shrink: 0;
            }

            .carousel-control:hover:not(:disabled) {
                background: #075E54;
                border-color: #075E54;
                color: white;
            }

            .carousel-control:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .carousel-control ion-icon {
                font-size: 14px;
            }

            /* Enhanced Label Filter Badges */
            .label-filter-badge {
                display: flex;
                align-items: center;
                padding: 6px 12px;
                border-radius: 20px;
                background-color: #f0f0f0;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 0.8rem;
                white-space: nowrap;
                flex-shrink: 0;
                border: 1px solid transparent;
                position: relative;
            }

            .label-filter-badge:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .label-filter-badge.active {
                background-color: #e3f2fd;
                border-color: #2196f3;
                font-weight: 600;
            }

            .label-color-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin-right: 6px;
                flex-shrink: 0;
            }

            .label-count {
                margin-left: 6px;
                font-size: 0.75rem;
                color: #666;
                background: rgba(0, 0, 0, 0.08);
                padding: 1px 6px;
                border-radius: 10px;
                min-width: 20px;
                text-align: center;
            }

            .label-filter-badge.active .label-count {
                background: rgba(33, 150, 243, 0.2);
                color: #1976d2;
            }

            /* Carousel Indicators */
            .carousel-indicators {
                display: flex;
                justify-content: center;
                gap: 4px;
                margin-top: 8px;
            }

            .carousel-indicator {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                background: #ddd;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .carousel-indicator.active {
                background: #075E54;
                transform: scale(1.2);
            }

            /* Empty State for Labels */
            .label-filters-empty {
                text-align: center;
                padding: 10px;
                color: #6c757d;
                font-size: 0.8rem;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .label-filters-container {
                    padding: 8px 10px;
                }

                .quick-filter-buttons {
                    flex-wrap: wrap;
                }

                .btn-filter {
                    flex: 1;
                    min-width: 80px;
                    justify-content: center;
                }

                .carousel-control {
                    width: 24px;
                    height: 24px;
                }

                .label-filter-badge {
                    padding: 5px 10px;
                    font-size: 0.75rem;
                }
            }

            /* Animation for filter changes */
            .chatlist {
                transition: opacity 0.3s ease;
            }

            .chatlist.filtering {
                opacity: 0.7;
            }

            /* Scrollbar styling for label carousel */
            .label-filters-track::-webkit-scrollbar {
                display: none;
            }

            .label-filters-track {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Error message styles */
            .message-status.error .icon {
                color: #dc3545 !important;
            }

            .error-message {
                font-size: 0.75em;
                color: #dc3545;
                margin-top: -8px;
                margin-bottom: 18px;
                padding: 4px 8px;
                background: rgba(220, 53, 69, 0.1);
                border-radius: 4px;
                border-left: 3px solid #dc3545;
                width: fit-content;
                float: right;
            }

            .message.friend_msg .error-message {
                background: rgba(220, 53, 69, 0.05);
            }

            /* Error status in chat list */
            .chatlist .block .error-indicator {
                color: #dc3545;
                font-size: 0.8em;
                margin-left: 5px;
            }

            /* Failed message styling */
            .message.failed {
                opacity: 0.7;
            }

            .message.failed p {
                color: #6c757d !important;
            }

            .full-width {
                width: 90vw;
                margin-left: calc(-45vw + 50%);
            }
        </style>
    @endsection

    @section('content')
        <div class="flex-grow-1 full-width">
            <div class="page-wrapper">
                <!-- Loading overlay -->
                <div class="loading-overlay">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading WhatsApp data...</p>
                </div>

                <!-- WhatsApp Container -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="whatsapp-container">
                                    <!-- Left Side - Chat List -->
                                    <div class="leftSide">
                                        <div class="header d-flex align-items-center">
                                            <!-- User Image -->
                                            <div class="userimg me-2">
                                                <img src="{{ auth()->user()->avatar_url ?? asset('/public/assets/images/whatsapp/default-contact.jpg') }}"
                                                    class="cover" alt="">
                                            </div>

                                            <!-- Custom Select for Accounts -->
                                            <div class="flex-grow-1 me-2">
                                                <select id="accountFilter" class="form-select form-select-sm custom-select">
                                                    <option value="">Select Account</option>
                                                    <!-- Accounts will be loaded via AJAX -->
                                                </select>
                                            </div>

                                            <!-- Ellipsis Dropdown -->
                                            <div class="dropdown">
                                                <a href="#" class="nav-link p-0" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <ion-icon name="ellipsis-vertical" size="large"></ion-icon>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item add-contact" href="javascript:void(0);"
                                                            id="newChatOption">
                                                            <ion-icon name="add-outline" class="me-2"></ion-icon> New Chat
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            id="manageLabelsBtn">
                                                            Manage Labels
                                                        </a>
                                                    </li>
                                                    <!-- Future options can be added here -->
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Search -->
                                        <div class="search_chat mt-2">
                                            <div>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Search or start new chat">
                                                <ion-icon name="search-outline"></ion-icon>
                                            </div>
                                        </div>

                                        <div class="label-filters-container">
                                            <!-- Quick Filter Buttons -->
                                            <div class="quick-filter-buttons">
                                                <button class="btn btn-filter active" data-filter="all">
                                                    <ion-icon name="chatbubbles-outline" class="me-1"></ion-icon> All
                                                </button>
                                                <button class="btn btn-filter" data-filter="unread">
                                                    <ion-icon name="mail-unread-outline" class="me-1"></ion-icon> Unread
                                                    <span class="unread-badge" id="unreadCountBadge"
                                                        style="display: none;">0</span>
                                                </button>
                                            </div>

                                            <!-- Label Filters Carousel -->
                                            <div class="label-filters-carousel">
                                                <button class="carousel-control prev" id="labelCarouselPrev">
                                                    <ion-icon name="chevron-back-outline"></ion-icon>
                                                </button>

                                                <div class="label-filters-wrapper">
                                                    <div class="label-filters-track" id="labelFilters">
                                                        <!-- Label filters will be loaded here -->
                                                    </div>
                                                </div>

                                                <button class="carousel-control next" id="labelCarouselNext">
                                                    <ion-icon name="chevron-forward-outline"></ion-icon>
                                                </button>
                                            </div>

                                            <!-- Carousel Indicators -->
                                            <div class="carousel-indicators" id="labelCarouselIndicators">
                                                <!-- Indicators will be dynamically added -->
                                            </div>
                                        </div>

                                        <!-- Chat List -->
                                        <div class="chatlist" id="chatList">
                                            <!-- Conversations will be loaded via AJAX -->
                                        </div>
                                    </div>


                                    <!-- Right Side - Chat Messages -->
                                    <div class="rightSide">
                                        <div class="empty-chat-state">
                                            <div class="content">
                                                <img src="{{ asset('public/assets/images/whatsapp/empty-chat.png') }}">
                                                <h5 class="mt-3 text-muted">Select a conversation to start chatting</h5>
                                            </div>
                                        </div>

                                        <div id="activeChatContainer" style="display: none; height: 100%;">
                                            {{-- <div class="label-filter-container" id="labelFilters">
                                                <!-- Label filters will be loaded here -->
                                            </div> --}}
                                            <div class="header">
                                                <div class="imgText">
                                                    <div class="userimg">
                                                        <img id="activeChatAvatar"
                                                            src="{{ asset('/public/assets/images/whatsapp/default-contact.jpg') }}"
                                                            class="cover" alt="">
                                                    </div>
                                                    <h4 id="activeChatName"></h4>
                                                </div>
                                                <ul class="nav_icons">
                                                    {{-- <li id="createLeadBtn"></li> --}}
                                                    <li><ion-icon class="li" name="search-outline"></ion-icon></li>
                                                    <li class="dropdown">
                                                        <ion-icon name="ellipsis-vertical" class="dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false"></ion-icon>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item edit-contact" href="#">Edit
                                                                    Contact</a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item delete-conversation text-danger"
                                                                    href="#">Delete Conversation</a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item block-contact"
                                                                    href="#">Block
                                                                    Contact</a></li>
                                                            <li><a class="dropdown-item unblock-contact" href="#"
                                                                    style="display: none;">Unblock Contact</a></li>
                                                        </ul>
                                                    </li>

                                                </ul>
                                            </div>

                                            <div class="chatbox" id="chatMessages">
                                                <!-- Messages will be loaded via AJAX -->
                                            </div>
                                            <div class="sticky-date-header" id="currentDateHeader"></div>

                                            <div class="chat_input">
                                                <div class="input-container">
                                                    <div class="message-input-wrapper">
                                                        <input type="text" name="message" id="messageInput"
                                                            placeholder="Type a message" class="text-input">

                                                        <div class="audio-recording-ui" style="display: none;">
                                                            <div class="recording-indicator">
                                                                <div class="pulse-animation"></div>
                                                                <span>Recording...</span>
                                                            </div>
                                                            <div class="recording-timer">0:00</div>
                                                            <button type="button" class="cancel-recording-btn">
                                                                <ion-icon name="close"></ion-icon>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <button type="button" id="templateBtn" class="btn btn-icon">
                                                        <ion-icon name="document-text-outline"></ion-icon>
                                                    </button>
                                                    <button type="button" id="attachMedia" class="btn btn-icon">
                                                        <ion-icon name="attach-outline"></ion-icon>
                                                    </button>

                                                    <button type="button" id="sendBtn" style="display: none;"
                                                        class="btn btn-icon send-btn">
                                                        <ion-icon name="send"></ion-icon>
                                                    </button>
                                                    <button type="button" id="recordBtn"
                                                        class="btn btn-icon record-btn">
                                                        <ion-icon name="mic"></ion-icon>
                                                    </button>
                                                </div>
                                                <!-- Hidden form for submission -->
                                                <form id="sendMessageForm" style="display: none;"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="conversation_id"
                                                        id="conversationIdInput">
                                                    <input type="hidden" name="account_id" id="accountIdInput">
                                                    <input type="file" id="mediaInput" name="media">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- sidebar for contact details -->
                                    <div class="contactSidebar" id="contactSidebar" style="display: none;">
                                        <div class="contact-header">
                                            <div class="contact-avatar">
                                                <img id="contactAvatar"
                                                    src="/public/assets/images/whatsapp/default-contact.jpg"
                                                    alt="Contact">
                                            </div>
                                            <div class="contact-info">
                                                <div>
                                                    <a id="ClientName" href="#" class="contact-name fw-bold"
                                                        target="_blank">
                                                        Loading...
                                                    </a>
                                                    <div id="contactTags" class="mt-1"></div>
                                                </div>
                                                <div class="contact-actions">
                                                    <button class="btn btn-sm btn-primary" id="viewProfileBtn"
                                                        title="View Profile">
                                                        <ion-icon name="person"></ion-icon>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" id="callContactBtn"
                                                        title="Call Contact">
                                                        <ion-icon name="call"></ion-icon>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" id="editContactModalBtn"
                                                        title="Edit Contact">
                                                        <ion-icon name="create"></ion-icon>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" id="createLeadSidebarBtn"
                                                        title="Create Lead">
                                                        <ion-icon name="storefront"></ion-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="contact-details-section">
                                            <h6 class="section-title">Contact Details</h6>
                                            <div class="detail-item">
                                                <div class="detail-label">Name</div>
                                                <div class="detail-value" id="displayClientName">-</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Phone 1</div>
                                                <div class="detail-value" id="displayClientPhone1">-</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Phone 2</div>
                                                <div class="detail-value" id="displayClientPhone2">-</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Address</div>
                                                <div class="detail-value adresse" id="displayClientAddress">-</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">City</div>
                                                <div class="detail-value" id="displayClientCity">-</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Notes</div>
                                                <div class="detail-value" id="displayClientNotes">-</div>
                                            </div>
                                        </div>

                                        <div class="assigned-agent-section mt-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="section-title mb-0">Assigned Agent</h6>
                                                <button class="btn btn-sm btn-outline-secondary" id="assignAgentBtn"
                                                    title="Assign to Agent">
                                                    Assign
                                                </button>
                                            </div>
                                            <div id="assignedAgentInfo">
                                                <div class="text-muted">No agent assigned</div>
                                            </div>
                                        </div>

                                        <div class="order-history-section mt-4">
                                            <h6 class="section-title">Order History</h6>
                                            <div id="orderHistoryList">
                                                <div class="text-muted">No order history</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add this modal for editing contact details -->
                                    <div class="modal fade" id="editContactDetailsModal" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Contact Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editContactDetailsForm">
                                                        <input type="hidden" id="editClientId">
                                                        <div class="mb-3">
                                                            <label for="editClientName" class="form-label">Name</label>
                                                            <input type="text" class="form-control"
                                                                id="editClientName" name="name" required>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="editClientPhone1" class="form-label">Phone
                                                                    1</label>
                                                                <input type="text" class="form-control"
                                                                    id="editClientPhone1" name="phone1">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="editClientPhone2" class="form-label">Phone
                                                                    2</label>
                                                                <input type="text" class="form-control"
                                                                    id="editClientPhone2" name="phone2">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editClientAddress"
                                                                class="form-label">Address</label>
                                                            <textarea class="form-control" id="editClientAddress" name="address" rows="2"></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editClientCity" class="form-label">City</label>
                                                            <select class="form-control" id="editClientCity"
                                                                name="id_city">
                                                                <option value="">Select City</option>
                                                                <!-- Cities will be loaded dynamically -->
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editClientNotes" class="form-label">Notes</label>
                                                            <textarea class="form-control" id="editClientNotes" name="seller_note" rows="2"></textarea>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="saveContactDetailsBtn">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assignment Modal -->
                                    <div class="modal fade" id="assignAgentModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign Conversation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="agentSelection">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" id="agentSearch"
                                                                placeholder="Search agents...">
                                                        </div>
                                                        <div id="agentsList" style="max-height: 400px; overflow-y: auto;">
                                                            <!-- Agents will be loaded here -->
                                                        </div>
                                                    </div>

                                                    <div id="assignmentReason" style="display: none;">
                                                        <div class="alert alert-info">
                                                            Assigning to: <strong id="selectedAgentName"></strong> (<span
                                                                id="selectedAgentRole"></span>)
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Priority</label>
                                                            <select class="form-select" id="assignmentPriority">
                                                                <option value="low">Low</option>
                                                                <option value="medium" selected>Medium</option>
                                                                <option value="high">High</option>
                                                                <option value="urgent">Urgent</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Reason for Assignment</label>
                                                            <textarea class="form-control" id="assignmentReasonText"
                                                                placeholder="Explain why this conversation needs special attention..." rows="3"></textarea>
                                                            <div class="form-text">
                                                                This will be visible to the assigned agent/manager.
                                                            </div>
                                                        </div>

                                                        <div class="alert alert-warning">
                                                            <small>
                                                                <strong>Note:</strong> Assigning to a manager should be
                                                                reserved for escalated issues
                                                                or complex customer problems that require managerial
                                                                attention.
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="confirmAssignmentBtn" style="display: none;">
                                                        Confirm Assignment
                                                    </button>
                                                    <input type="hidden" id="selectedAgentId">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="newChatModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newChatForm">
                            <div class="mb-3">
                                <label for="contactNumber" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="contactNumber"
                                    placeholder="e.g. 212612345678" required>
                                <small class="text-muted">Include country code without + or 00</small>
                            </div>
                            <div class="mb-3">
                                <label for="contactName" class="form-label">Contact Name (Optional)</label>
                                <input type="text" class="form-control" id="contactName" placeholder="e.g. John Doe">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="startNewChatBtn" class="btn btn-primary">Start Chat</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editContactForm">
                            <input type="hidden" id="editConversationId">
                            <div class="mb-3">
                                <label for="editContactName" class="form-label">Contact Name</label>
                                <input type="text" class="form-control" id="editContactName" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="saveContactBtn" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Label Modal -->
        <div class="modal fade" id="createLabelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Label</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createLabelForm">
                            <div class="mb-3">
                                <label for="labelName" class="form-label">Label Name</label>
                                <input type="text" class="form-control" id="labelName" required>
                            </div>
                            <div class="mb-3">
                                <label for="labelColor" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="labelColor"
                                    value="#7e8da1">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="saveLabelBtn" class="btn btn-primary">Create Label</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="labelsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Labels</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="labelsContainer">
                            <!-- Labels will be loaded here -->
                        </div>

                        <div class="mt-4">
                            <h6>Create New Label</h6>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="newLabelName" placeholder="Label name">
                                <input type="color" class="form-control form-control-color" id="newLabelColor"
                                    value="#7e8da1" title="Choose color">
                                <button class="btn btn-primary" type="button" id="addLabelBtn">
                                    <ion-icon name="add-outline"></ion-icon> Add
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveLabelsBtn">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="whatsappLeadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 720px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New WhatsApp Lead</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="whatsappLeadForm">
                            @csrf
                            <input type="hidden" id="whatsapp_contact_number">
                            <input type="hidden" id="whatsapp_contact_name">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="lead_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" class="form-control" id="lead_mobile" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mobile 2 (Optional)</label>
                                    <input type="text" class="form-control" id="lead_mobile2">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <select class="form-control" id="lead_city" required>
                                        <option value="">Select City</option>
                                        @foreach ($cities as $v_city)
                                            <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                Product
                                            </th>
                                            <th class="text-center">
                                                Quantity
                                            </th>
                                            <th class="text-center">
                                                Price
                                            </th>
                                            <th>
                                                <a id="add_row" class="btn btn-primary float-right text-white"
                                                    style="font-size:10px" style="width: 83px;">Add Row</a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id='addr0' data-id="0" class="hidden">
                                            <td data-name="name">
                                                <select id="product_lead" class="form-control product_lead"
                                                    name="product_lead">
                                                    <option value="">Select Option</option>
                                                    @foreach ($proo as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-price="{{ $product->price }}">{{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td data-name="mail">
                                                <input type="number" name="lead_quantity" id="lead_quantity"
                                                    class="form-control lead_quantity" placeholder='quantity' />
                                            </td>
                                            <td data-name="desc">
                                                <input type="number" name="price_lead" placeholder="price"
                                                    id="price_lead" class="form-control price_lead" />
                                            </td>
                                            <td data-name="del">
                                                <button name="del0"
                                                    class='btn btn-danger glyphicon glyphicon-remove row-remove'><span
                                                        aria-hidden="true">×</span></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" id="lead_address" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dlivery Date</label>
                                <input type="date" class="form-control" id="lead_date_delivered">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Price</label>
                                <input type="text" class="form-control" id="lead_total">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveWhatsappLead">Save Lead</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Template Selector Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="templateOffcanvas"
            aria-labelledby="templateOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="templateOffcanvasLabel">Message Templates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Search and Filters -->
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search templates..."
                            id="templateSearch">
                        <button class="btn btn-outline-secondary" type="button" id="templateSearchBtn">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <select class="form-select" id="templateLanguageFilter">
                        <option value="">All Languages</option>
                        <option value="en_US">English</option>
                        <option value="ar_AR">Arabic</option>
                        <option value="es_ES">Spanish</option>
                        <!-- Add more language options as needed -->
                    </select>
                </div>

                <!-- Templates List -->
                <div id="templatesList" class="templates-container">
                    <!-- Templates will be loaded here -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading templates...</span>
                        </div>
                        <p class="mt-2">Loading templates...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Preview Modal -->
        <div class="modal fade" id="templatePreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Use Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="preview-container mb-4">
                            <div class="preview-header">
                                <div class="avatar">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="name">Business Name</div>
                            </div>
                            <div class="preview-body">
                                <div class="preview-message incoming">
                                    Template preview
                                </div>
                                <div class="preview-message outgoing" id="templatePreviewContent">
                                    <!-- Template preview will be loaded here -->
                                </div>
                            </div>
                        </div>
                        <div class="media-preview-container mb-4" id="mediaPreviewContainer" style="display: none;">
                            <h6>Header Media Preview</h6>
                            <div class="media-preview" id="mediaPreview">
                                <!-- Media will be previewed here -->
                            </div>
                            <div class="mt-2">
                                <label for="headerMedia" class="btn btn-sm btn-outline-primary">
                                    <ion-icon name="camera-outline"></ion-icon> Change Media
                                </label>
                                <input type="file" class="d-none" id="headerMedia"
                                    accept="image/*,video/*,.pdf,.doc,.docx,.txt">
                                <small class="text-muted d-block mt-1">Required: Image, Video or Document</small>
                            </div>
                        </div>
                        <form id="templateVariablesForm">
                            <input type="hidden" id="selectedTemplateName">
                            <input type="hidden" id="selectedTemplateLanguage">

                            <div id="templateVariablesContainer">
                                <!-- Variables will be dynamically added here -->
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="sendTemplateBtn">Send Message</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Search Messages Modal -->
        <div class="modal fade" id="searchMessagesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center w-100">
                            <div class="input-group flex-grow-1 me-3">
                                <span class="input-group-text bg-transparent border-0">
                                    <ion-icon name="search-outline"></ion-icon>
                                </span>
                                <input type="text" class="form-control border-0" id="messageSearchInput"
                                    placeholder="Search messages... (Ctrl+F)" autocomplete="off">
                                <button type="button" class="btn btn-outline-primary border-0" id="voiceSearchBtn"
                                    title="Voice Search">
                                    <ion-icon name="mic-outline"></ion-icon>
                                </button>
                                <button type="button" class="btn btn-outline-primary border-0" id="advancedSearchBtn"
                                    title="Advanced Search">
                                    <ion-icon name="options-outline"></ion-icon>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Search Options -->
                    <div id="advancedSearchOptions" class="advanced-search-options border-bottom" style="display: none;">
                        <div class="p-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Date Range</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" class="form-control" id="searchDateFrom"
                                            placeholder="From">
                                        <span class="input-group-text">to</span>
                                        <input type="date" class="form-control" id="searchDateTo" placeholder="To">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Message Type</label>
                                    <select class="form-select form-select-sm" id="searchMessageType">
                                        <option value="all">All Messages</option>
                                        <option value="text">Text Only</option>
                                        <option value="media">Media Messages</option>
                                        <option value="template">Templates</option>
                                        <option value="deleted">Deleted Messages</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Sender</label>
                                    <select class="form-select form-select-sm" id="searchSender">
                                        <option value="all">Everyone</option>
                                        <option value="me">Only Me</option>
                                        <option value="them">Only Them</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-primary btn-sm w-100"
                                            id="applyAdvancedSearch">
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="search-results-container">
                            <div class="search-info-bar p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span id="searchResultsCount" class="text-muted">Enter search term</span>
                                        <small id="searchFiltersInfo" class="text-muted ms-2"
                                            style="display: none;"></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span id="currentResultPosition" class="text-muted me-2"
                                            style="display: none;"></span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="matchCase">
                                            <label class="form-check-label small" for="matchCase">Match Case</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="searchResults" class="search-results-list">
                                <!-- Search results will be displayed here -->
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <small class="text-muted me-auto">
                            • <kbd>Esc</kbd> Close
                        </small>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" id="searchInOlderMessages"
                            style="display: none;">
                            Search in Older Messages
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voice Search Indicator -->
        <div id="voiceSearchIndicator" class="voice-search-indicator" style="display: none;">
            <div class="voice-search-popup">
                <div class="pulse-animation"></div>
                <div class="voice-search-text">Listening... Speak now</div>
                <button type="button" class="btn btn-sm btn-danger mt-2" id="stopVoiceSearch">Stop</button>
            </div>
        </div>
        <input type="hidden" id="currentUserId" value="{{ Auth::id() }}">
    @endsection

    @section('script')
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script>
            $(document).ready(function() {
                const accountId = $('#accountFilter').val();
                let mediaRecorder;
                let audioChunks = [];
                let recordingTimer;
                let recordingStartTime;
                let audioBlob = null;
                let isRecording = false;
                let currentActiveChat = null;
                let currentActiveAccount = null;
                let pusher = null;
                let channel = null;
                let currentQuotedMessage = null;
                let currentLabelFilter = 'all';
                let currentQuickFilter = 'all';
                let searchResults = [];
                let currentSearchIndex = -1;
                let searchTerm = '';
                let searchFilters = {
                    dateFrom: null,
                    dateTo: null,
                    messageType: 'all',
                    sender: 'all',
                    matchCase: false
                };
                let voiceRecognition = null;
                let isVoiceSearchActive = false;
                let $track = $(".label-filters-track");
                let $wrapper = $(".label-filters-wrapper");
                let currentIndex = 0;

                function getItemWidth() {
                    return $track.children().first().outerWidth(true) || 120;
                }

                function getVisibleCount() {
                    return Math.floor($wrapper.width() / getItemWidth());
                }

                function updateCarousel() {
                    let itemWidth = getItemWidth();
                    let offset = -(currentIndex * itemWidth);
                    $track.css("transform", `translateX(${offset}px)`);
                }

                $("#labelCarouselNext").on("click", function() {
                    let totalItems = $track.children().length;
                    let visibleCount = getVisibleCount();
                    if (currentIndex < totalItems - visibleCount) {
                        currentIndex++;
                        updateCarousel();
                    }
                });

                $("#labelCarouselPrev").on("click", function() {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateCarousel();
                    }
                });

                $(window).on("resize", function() {
                    updateCarousel();
                });
                if (accountId) {
                    loadConversationsByFilter('all');
                }






                initApplication();

                function initPusher(accountId) {
                    if (pusher) {
                        pusher.disconnect();
                    }

                    pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                        encrypted: true
                    });

                    channel = pusher.subscribe('whatsapp.' + accountId);
                    const userId = $("#currentUserId").val();
                    const userChannel = pusher.subscribe('private-user.' + userId);
                    userChannel.bind('conversation.assigned', function(data) {
                        handleConversationAssigned(data);
                    });

                    channel.bind('NewMessage', function(data) {
                        const message = data.message;
                        const contact_number = data.contact_number;
                        const contact_name = data.contact_name;

                        const isCurrentChat = message.whats_app_conversation_id == currentActiveChat;

                        if (isCurrentChat) {
                            appendMessage(message);
                            if (message.direction === 'in') {
                                markAsRead(currentActiveChat);
                            }
                        } else {
                            toastr.info(`New message from ${message.contact_name || message.from}`,
                                'New Message');
                            updateConversationList(message, contact_number, contact_name);
                            const $conversation = $(`.block[data-id="${message.whats_app_conversation_id}"]`);
                            if (!$conversation.hasClass('unread')) {
                                incrementUnreadBadge();
                            }
                        }
                        initMessageActions()
                    });

                    channel.bind('MessageUpdate', function(data) {
                        if (data.conversation_id == currentActiveChat) {
                            updateMessageStatus(data.id, data.status, data.error_data);
                        }
                    });
                }

                function handleConversationAssigned(data) {
                    const assignment = data.assignment;
                    const conversation = data.conversation;

                    toastr.info(
                        `You have been assigned a conversation with ${conversation.contact_name} (${assignment.priority} priority)`,
                        'New Assignment');

                    if (currentActiveChat === conversation.id) {
                        updateAssignmentUI(assignment);
                    }

                    updateConversationAssignmentBadge(conversation.id, assignment);
                }


                function updateConversationAssignmentBadge(conversationId, assignment) {
                    const $conversation = $(`.block[data-id="${conversationId}"]`);
                    if ($conversation.length) {
                        $conversation.find('.assignment-badge').remove();

                        const badgeHtml = `
                            <div class="assignment-badge priority-${assignment.priority}" 
                                title="Assigned to ${assignment.assigned_to.name} (${assignment.priority} priority)">
                                <ion-icon name="person-circle-outline"></ion-icon>
                                ${assignment.priority === 'urgent' ? '!' : ''}
                            </div>
                        `;

                        $conversation.find('.details').append(badgeHtml);
                    }
                }

                function incrementUnreadBadge() {
                    const $unreadBadge = $('#unreadCountBadge');
                    let currentCount = parseInt($unreadBadge.text()) || 0;
                    currentCount++;
                    $unreadBadge.text(currentCount).show();
                }

                function decrementUnreadBadge() {
                    const $unreadBadge = $('#unreadCountBadge');
                    let currentCount = parseInt($unreadBadge.text()) || 0;
                    if (currentCount > 0) {
                        currentCount--;
                        if (currentCount === 0) {
                            $unreadBadge.hide();
                        } else {
                            $unreadBadge.text(currentCount);
                        }
                    }
                }


                function updateConversationList(message, contact_number = null, contact_name = null) {
                    let $conversation = $(`.block[data-id="${message.whats_app_conversation_id}"]`);
                    const contactName = contact_name || ($conversation.length ? $conversation.find(
                        '.listHead h4').text() : 'Unknown');
                    const contactNumber = contact_number || 'Unknown number';
                    let lastMessage = message.deleted ? 'This message has been deleted' : (message.body ||
                        'Media message');
                    const isCurrentActive = currentActiveChat == message.whats_app_conversation_id;

                    if (message.direction == 'out' && message.status == 'failed') {
                        lastMessage = '⚠️!' + lastMessage;
                    }

                    if ($conversation.length === 0) {
                        const conversationHtml = `
                            <div class="block chat_block ${message.unread_count > 0 ? 'unread' : ''} ${message.status === 'failed' ? 'has-error' : ''}" 
                                data-id="${message.whats_app_conversation_id}" 
                                data-contact="${message.from}">
                                <div class="imgBox">
                                    <img src="${message.contact_avatar || '/public/assets/images/whatsapp/default-contact.jpg'}" class="cover" alt="">
                                </div>
                                <div class="details">
                                    <div class="listHead">
                                        <h4>${contactName} (+${contactNumber})</h4>
                                        <p class="time">${formatTime(message.created_at)}</p>
                                    </div>
                                    <div class="message_p">
                                        <p>${lastMessage}</p>
                                        ${isCurrentActive ? '' : (message.unread_count > 0 ? `<b>${message.unread_count}</b>` : '')}
                                        ${message.status === 'failed' ? '<span class="error-indicator" title="Message failed">⚠️</span>' : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#chatList').prepend(conversationHtml);
                    } else {
                        $conversation.find('.message_p p').text(lastMessage);
                        $conversation.find('.time').text(formatTime(message.created_at));
                        $conversation.prependTo('#chatList');

                        if (message.unread_count == 0) {
                            $conversation.removeClass('unread');
                            $conversation.find('.message_p b').remove();
                        } else {
                            $conversation.addClass('unread');
                            const $badge = $conversation.find('.message_p b');
                            if ($badge.length) {
                                $badge.text(message.unread_count);
                            } else {
                                $conversation.find('.message_p').append(`<b>${message.unread_count}</b>`);
                            }
                        }
                    }
                }

                $(document).on('keyup', '.search_chat input', function() {
                    const query = $(this).val().toLowerCase().trim();
                    let matches = 0;

                    $('#chatList .block').each(function() {
                        const $conv = $(this);
                        const name = $conv.find('.listHead h4').text().toLowerCase();
                        const number = $conv.data('contact') ? $conv.data('contact').toString()
                            .toLowerCase() : '';

                        $conv.find('.listHead h4').html($conv.find('.listHead h4').text());

                        if (query === '' || name.includes(query) || number.includes(query)) {
                            $conv.show();
                            matches++;

                            if (query !== '') {
                                const regex = new RegExp(`(${query})`, 'gi');
                                const originalName = $conv.find('.listHead h4').text();
                                const highlighted = originalName.replace(regex,
                                    '<span class="highlight">$1</span>');
                                $conv.find('.listHead h4').html(highlighted);
                            }
                        } else {
                            $conv.hide();
                        }
                    });

                    $('#noResults').remove();
                    if (matches === 0) {
                        $('#chatList').append(
                            '<div id="noResults" class="text-center text-muted mt-2">No conversation found</div>'
                        );
                    }
                });


                function updateMessageStatus(messageId, status, errorData = null) {
                    const messageElement = $(`.message[data-id="${messageId}"]`);

                    if (messageElement.length) {
                        let statusHtml = '';

                        if (status === 'sent') {
                            statusHtml =
                                '<span class="message-status"><ion-icon class="icon" name="checkmark-outline"></ion-icon></span>';
                        } else if (status === 'delivered') {
                            statusHtml =
                                '<span class="message-status"><ion-icon class="icon" style="margin-right: -8px;" name="checkmark-outline"></ion-icon><ion-icon class="icon" name="checkmark-outline"></ion-icon></span>';
                        } else if (status === 'read') {
                            statusHtml =
                                '<span class="message-status"><ion-icon class="icon read" style="margin-right: -8px;" name="checkmark-outline"></ion-icon><ion-icon class="icon read" name="checkmark-outline"></ion-icon></span>';
                        } else if (status === 'failed') {
                            statusHtml =
                                '<span class="message-status error" title="Message failed to send"><ion-icon class="icon error" name="warning-outline"></ion-icon></span>';

                            if (errorData) {
                                const errorMessage = getErrorMessage(errorData);
                                console.log('Displaying error message:', errorMessage);
                                messageElement.after(
                                    `<div class="error-message">${errorMessage}</div>`
                                );
                            }

                        }

                        messageElement.find('.message-status').remove();
                        messageElement.find('.error-message').remove();

                        const timestampElement = messageElement.find('p .timestamp').first();
                        if (timestampElement.length) {
                            timestampElement.append(statusHtml);
                        }

                        if (status === 'read' && messageElement.find('.audio-player').length) {
                            messageElement.find('.audio-player .play-btn').addClass('audio-message-played');
                        }
                    }
                }

                function getErrorMessage(errorData) {
                    if (!errorData) return 'Failed to send message';

                    try {
                        const errors = typeof errorData === 'string' ? JSON.parse(errorData) : errorData;
                        if (errors.errors && errors.errors.length > 0) {
                            const error = errors.errors[0];
                            console.log('Processing error:', error);

                            switch (error.code) {
                                case 131047:
                                    return 'Message failed: 24-hour window expired. Customer needs to initiate conversation.';
                                case 131026:
                                    return 'Message failed: Payment required for WhatsApp Business API.';
                                case 131051:
                                    return 'Message failed: Template not found or not approved.';
                                default:
                                    return error.message || `Message failed: ${error.title || 'Unknown error'}`;
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing error data:', e);
                    }

                    return 'Failed to send message';
                }

                $('#newChatBtn').on('click', function() {
                    $('#newChatModal').modal('show');
                });

                $(document).on('click', '.edit-contact', function(e) {
                    e.preventDefault();

                    if (!currentActiveChat) return;

                    const contactName = $('#activeChatName').text();
                    const contactNumber = $('.chatlist .block.active').data('contact');

                    $('#editConversationId').val(currentActiveChat);
                    $('#editContactName').val(contactName);
                    $('#editContactNumber').val(contactNumber);

                    $('#editContactModal').modal('show');
                });


                $(document).on('click', '.add-contact', function(e) {
                    e.preventDefault();

                    $('#newChatModal').modal('show');
                });

                $('#saveContactBtn').on('click', async function() {
                    const saveBtn = $(this);
                    const conversationId = $('#editConversationId').val();
                    const newName = $('#editContactName').val().trim();
                    const accountId = $('#accountFilter').val();

                    if (!newName) {
                        toastr.error('Please fill all fields');
                        return;
                    }

                    saveBtn.prop('disabled', true);

                    try {
                        const isDuplicate = await checkDuplicateContact(accountId, newNumber);

                        if (isDuplicate) {
                            toastr.error('A conversation with this number already exists');
                            saveBtn.prop('disabled', false);
                            return;
                        }

                        $.ajax({
                            url: '/whatsapp-template/update-contact/' + conversationId,
                            method: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                contact_name: newName,
                            },
                            success: function() {
                                $('#activeChatName').text(newName);
                                $('.chatlist .block.active .details .listHead h4')
                                    .text(newName);
                                $('#editContactModal').modal('hide');
                                toastr.success(
                                    'Contact updated successfully');

                            },
                            error: function() {
                                toastr.error('Failed to update contact');
                            },
                            complete: function() {
                                saveBtn.prop('disabled', false);
                            }
                        });
                    } catch (error) {
                        console.log('Error checking contact:', error);
                        toastr.error('Error checking contact');
                    }
                });

                $(document).on('click', '.delete-conversation', function(e) {
                    e.preventDefault();
                    const deleteBtn = $(this);

                    if (!currentActiveChat) return;

                    Swal.fire({
                        title: 'Delete Conversation',
                        text: 'Are you sure you want to delete this conversation? All messages will be lost.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/whatsapp-template/conversations/' + currentActiveChat,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    toastr.success('Conversation deleted successfully');
                                    $('.chatlist .block.active').remove();
                                    $('.empty-chat-state').show();
                                    $('#activeChatContainer').hide();
                                    currentActiveChat = null;
                                    updateUrlToRemoveActiveChat();

                                },
                                error: function() {
                                    toastr.error('Failed to delete conversation');
                                },
                                complete: function() {
                                    deleteBtn.prop('disabled', false);
                                }
                            });
                        }
                    });
                });

                function updateUrlToRemoveActiveChat() {
                    const searchParams = new URLSearchParams(window.location.search);

                    if (searchParams.has('active_chat')) {
                        searchParams.delete('active_chat');

                        const newUrl = window.location.pathname +
                            (searchParams.toString() ? '?' + searchParams.toString() : '') +
                            window.location.hash;

                        window.history.replaceState({}, '', newUrl);
                    }
                }

                function checkDuplicateContact(accountId, phoneNumber, currentConversationId = null) {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: '/whatsapp-template/check-contact',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                account_id: accountId,
                                contact_number: phoneNumber,
                                conversation_id: currentConversationId
                            },
                            success: function(response) {
                                resolve(response.exists);
                            },
                            error: function() {
                                reject(false);
                            }
                        });
                    });
                }

                $('#startNewChatBtn').on('click', async function() {
                    const phoneNumber = $('#contactNumber').val().trim();
                    const contactName = $('#contactName').val().trim() || phoneNumber;
                    const accountId = $('#accountFilter').val();
                    const conversationId = $('#conversationIdInput').val();

                    if (!phoneNumber) {
                        toastr.error('Please enter a phone number');
                        return;
                    }

                    if (!accountId) {
                        toastr.error('Please select an account');
                        return;
                    }

                    if (phoneNumber.startsWith('+') || phoneNumber.startsWith('00')) {
                        toastr.error('Phone number should not start with "+" or "00"');
                        return;
                    }

                    if (!/^\d+$/.test(phoneNumber)) {
                        toastr.error('Phone number should contain only digits');
                        return;
                    }

                    showLoading();

                    try {
                        const isDuplicate = await checkDuplicateContact(accountId, phoneNumber,
                            conversationId);

                        if (isDuplicate) {
                            toastr.error('A conversation with this number already exists');
                            hideLoading();
                            return;
                        }

                        $('#newChatModal').modal('hide');



                        $.ajax({
                            url: '{{ route('whatsapp-template.check-conversation') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                account_id: accountId,
                                contact_number: phoneNumber,
                                contact_name: contactName
                            },
                            success: function(response) {
                                if (response.success) {
                                    loadMessages(accountId, response.conversation.id);
                                    loadConversations(accountId);
                                    loadContactDetails(phoneNumber);
                                } else {
                                    toastr.error(response.message || 'Failed to start chat');
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Failed to start chat');
                            },
                            complete: function() {
                                hideLoading();
                            }
                        });
                    } catch (error) {
                        hideLoading();
                        toastr.error('Error checking contact');
                    }
                });

                function setupReplyHandlers() {
                    $(document).on('click', '.message:not(.my_msg)', function() {
                        const messageId = $(this).data('id');
                        currentQuotedMessage = messageId;

                        $('#messageInput').attr('placeholder', 'Replying to message...');
                        $('#messageInput').focus();
                    });
                }

                setupReplyHandlers()

                function clearReply() {
                    currentQuotedMessage = null;
                    $('#messageInput').attr('placeholder', 'Type a message');
                }

                function initApplication() {
                    showLoading();

                    loadAccounts()
                        .then(accounts => {
                            const urlParams = new URLSearchParams(window.location.search);
                            const urlAccountId = urlParams.get('account_id');
                            const urlActiveChat = urlParams.get('active_chat');

                            if (urlAccountId && accounts.find(a => a.id == urlAccountId)) {
                                currentActiveAccount = urlAccountId;
                                $('#accountFilter').val(urlAccountId);
                                initPusher(urlAccountId);
                                loadLabelFilters(urlAccountId);

                                return loadConversations(urlAccountId)
                                    .then(() => {
                                        const initialUnreadCount = calculateTotalUnreadConversations();
                                        updateUnreadBadge(initialUnreadCount);

                                        if (urlActiveChat) {
                                            return loadMessages(urlAccountId, urlActiveChat);
                                        }
                                    });
                            } else if (accounts.length > 0) {
                                currentActiveAccount = accounts[0].id;
                                $('#accountFilter').val(accounts[0].id);
                                initPusher(accounts[0].id);
                                loadLabelFilters(accounts[0].id);
                                return loadConversations(accounts[0].id)
                                    .then(() => {
                                        const initialUnreadCount = calculateTotalUnreadConversations();
                                        updateUnreadBadge(initialUnreadCount);
                                    });
                            }
                        })
                        .catch(error => {
                            console.error('Initialization error:', error);
                            toastr.error('Failed to initialize WhatsApp');
                        })
                        .finally(() => {
                            hideLoading();
                        });
                }

                function showLoading() {
                    $('.loading-overlay').removeClass('hidden');
                }

                function hideLoading() {
                    $('.loading-overlay').addClass('hidden');
                }

                function loadAccounts() {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: '{{ route('whatsapp-template.get-accounts') }}',
                            method: 'GET',
                            success: function(response) {
                                if (response.success && response.accounts) {
                                    $('#accountFilter').empty();
                                    $('#accountFilter').append(
                                        '<option value="">Select Account</option>');

                                    response.accounts.forEach(account => {
                                        let optionText = account.phone_number;
                                        if (account.user_name) {
                                            optionText += ` (${account.user_name})`;
                                        }

                                        $('#accountFilter').append(
                                            `<option value="${account.id}">${optionText}</option>`
                                        );
                                    });

                                    resolve(response.accounts);
                                } else {
                                    reject(response.message || 'Failed to load accounts');
                                }
                            },
                            error: function(xhr) {
                                reject(xhr.responseText || 'Failed to load accounts');
                            }
                        });
                    });
                }

                function calculateTotalUnreadCount() {
                    let totalUnread = 0;
                    $('#chatList .block').each(function() {
                        const unreadBadge = $(this).find('.message_p b');
                        if (unreadBadge.length) {
                            totalUnread += parseInt(unreadBadge.text()) || 0;
                        }
                    });
                    return totalUnread;
                }


                function loadConversations(accountId) {
                    showLoading();

                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: '{{ route('whatsapp-template.get-conversations') }}',
                            method: 'GET',
                            data: {
                                account_id: accountId
                            },
                            success: function(response) {
                                if (response.success && response.conversations) {
                                    $('#chatList').empty();
                                    const conversationsArray = Object.values(response
                                        .conversations);
                                    const unreadConversationsCount = countUnreadConversations(
                                        conversationsArray);
                                    updateUnreadBadge(unreadConversationsCount);
                                    conversationsArray.forEach(conversation => {
                                        const conversationHtml = `
                                            <div class="block chat_block ${conversation.unread_count > 0 ? 'unread' : ''}" 
                                                data-id="${conversation.id}" 
                                                data-contact="${conversation.contact_number}">
                                                <div class="imgBox">
                                                    <img src="{{ asset('/public/assets/images/whatsapp/default-contact.jpg') }}" class="cover" alt="">
                                                </div>
                                                <div class="details">
                                                    <div class="listHead">
                                                        <h4>${conversation.contact_name} (+${conversation.contact_number})</h4>
                                                        <p class="time">${formatTime(conversation.last_message_at)}</p>
                                                    </div>
                                                    <div class="message_p">
                                                        <p>${conversation.last_message}</p>
                                                        ${conversation.unread_count > 0 ? `<b>${conversation.unread_count}</b>` : ''}
                                                    </div>
                                                </div>
                                            </div>
                                        `;

                                        $('#chatList').append(conversationHtml);
                                    });

                                    resolve(conversationsArray);
                                } else {
                                    reject(response.message ||
                                        'Failed to load conversations');
                                }
                            },
                            error: function(xhr) {
                                reject(xhr.responseText || 'Failed to load conversations');
                            },
                            complete: function() {
                                hideLoading();
                            }
                        });
                    });
                }

                let lastMessageDate;

                function loadMessages(accountId, conversationId) {
                    showLoading();

                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: '{{ route('whatsapp-template.get-messages') }}',
                            method: 'GET',
                            data: {
                                account_id: accountId,
                                conversation_id: conversationId
                            },
                            success: function(response) {
                                if (response.success) {
                                    currentActiveChat = conversationId;
                                    currentActiveAccount = accountId;
                                    initPusher(accountId);
                                    updateBlockUI(response.conversation.is_blocked || false);

                                    $('.chatlist .block').removeClass('active');
                                    $(`.chatlist .block[data-id="${conversationId}"]`).addClass(
                                        'active');

                                    $('.empty-chat-state').hide();
                                    $('#activeChatContainer').show();

                                    $('#activeChatName').text(
                                        `${response.conversation.contact_name} (+${response.conversation.contact_number})`
                                    );
                                    $('#activeChatAvatar').attr('src',
                                        '{{ asset('/public/assets/images/whatsapp/default-contact.jpg') }}'
                                    );

                                    $('#conversationIdInput').val(conversationId);
                                    $('#accountIdInput').val(accountId);

                                    $('#chatMessages').empty();

                                    if (response.messages && response.messages.length > 0) {
                                        let currentDate = null;
                                        let lastSeparatorDate = null;
                                        const contactNumber = response.conversation.contact_number
                                        loadContactDetails(contactNumber);

                                        response.messages.forEach((message, index) => {
                                            const messageDate = new Date(message.created_at)
                                                .toDateString();

                                            if (currentDate !== messageDate) {
                                                currentDate = messageDate;
                                                appendDateSeparator(message.created_at);
                                                lastSeparatorDate = message.created_at;
                                            }
                                            appendMessage(message);
                                        });

                                        if (lastSeparatorDate) {
                                            const formattedDate = formatStickyDate(new Date(
                                                lastSeparatorDate));
                                            $('#currentDateHeader').text(formattedDate).show();
                                        }
                                    }

                                    scrollToBottom();
                                    updateUrlParams({
                                        account_id: accountId,
                                        active_chat: conversationId
                                    });

                                    resolve(response.messages);
                                } else {
                                    reject(response.message || 'Failed to load messages');
                                }
                            },
                            error: function(xhr) {
                                reject(xhr.responseText || 'Failed to load messages');
                            },
                            complete: function() {
                                hideLoading();
                            }
                        });
                    });
                }

                $("#chatMessages").scroll(function() {
                    console.log("scrolling");
                    initStickyDates();

                });

                function formatStickyDate(date) {
                    const today = new Date();
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);

                    if (date.toDateString() === today.toDateString()) {
                        return 'Today';
                    } else if (date.toDateString() === yesterday.toDateString()) {
                        return 'Yesterday';
                    } else {
                        return date.toLocaleDateString('en-US', {
                            weekday: 'long',
                            month: 'long',
                            day: 'numeric',
                            year: date.getFullYear() !== today.getFullYear() ? 'numeric' : undefined
                        });
                    }
                }

                function appendDateSeparator(dateString) {
                    const date = new Date(dateString);
                    const dateText = formatStickyDate(date);

                    const separatorHtml = `
                        <div class="date-separator">
                            <span>${dateText}</span>
                        </div>
                    `;

                    $('#chatMessages').append(separatorHtml);
                }

                function initStickyDates() {
                    const chatbox = document.getElementById('chatMessages');
                    const dateHeader = document.getElementById('currentDateHeader');
                    const dateSeparators = Array.from(chatbox.querySelectorAll('.date-separator'));

                    const observer = new IntersectionObserver(
                        (entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const dateText = entry.target.textContent;
                                    dateHeader.textContent = dateText;
                                }
                            });
                        }, {
                            threshold: 0.5
                        }
                    );

                    dateSeparators.forEach(separator => {
                        observer.observe(separator);
                    });
                }

                function formatTime(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }

                function appendMessage(message) {
                    const messageClass = message.direction === 'out' ? 'my_msg' : 'friend_msg';
                    let mediaHtml = '';
                    let messageBodyHtml = '';
                    let replyHtml = '';
                    let statusHtml = '';

                    const isFailed = message.status === 'failed';
                    const failedClass = isFailed ? 'failed' : '';


                    if (message.direction === 'out') {
                        if (message.status === 'sent') {
                            statusHtml =
                                '<span class="message-status"><ion-icon class="icon" name="checkmark-outline"></ion-icon></span>';
                        } else if (message.status === 'delivered') {
                            statusHtml =
                                '<span class="message-status"><ion-icon class="icon" style="margin-right: -8px;" name="checkmark-outline"></ion-icon><ion-icon class="icon" name="checkmark-outline"></ion-icon></span>';
                        } else if (message.status === 'read') {
                            statusHtml =
                                '<span class="message-status"><ion-icon class="icon read" style="margin-right: -8px;" name="checkmark-outline"></ion-icon><ion-icon class="icon read" name="checkmark-outline"></ion-icon></span>';
                        } else if (message.status === 'failed') {
                            statusHtml =
                                '<span class="message-status error" title="Message failed to send"><ion-icon class="icon error" name="warning-outline"></ion-icon></span>';
                        }
                    }

                    let errorHtml = '';
                    if (isFailed && message.error_data) {
                        const errorMessage = getErrorMessage(message.error_data);
                        errorHtml = `<div class="error-message">${errorMessage}</div>`;
                    }

                    if (message.quoted_message) {
                        const quotedMsg = message.quoted_message;
                        const quotedText = quotedMsg.body || getMediaTypeText(quotedMsg);
                        const quotedSender = quotedMsg.direction === 'out' ? 'You' : message.conversation.contact_name;

                        replyHtml = `
                            <div class="reply-container" data-quoted-id="${quotedMsg.id}">
                                <div class="reply-line"></div>
                                <div class="reply-content">
                                    <div class="reply-sender">${quotedSender}</div>
                                    <div class="reply-text">${quotedText}</div>
                                </div>
                            </div>
                        `;
                    }

                    const messageActions = message.direction === 'out' && message.deleted == false ? `
                        <div class="message-actions">
                            <button class="message-actions-btn" data-message-id="${message.message_id}" data-id="${message.id}">
                                <ion-icon name="chevron-down"></ion-icon>
                            </button>
                            <div class="message-actions-menu" id="actionsMenu${message.id}">
                                <li class="delete" data-message-id="${message.message_id}">Delete</li>
                            </div>
                        </div>
                    ` : '';

                    if (message.media && message.media.length > 0) {
                        const timestamp = formatTime(message.created_at);
                        const isAudio = message.media[0].mime_type.startsWith('audio/');
                        const playedClass = message.status === 'read' && isAudio ? 'audio-message-played' : '';

                        message.media.forEach(mediaItem => {
                            if (mediaItem.mime_type.startsWith('image/')) {
                                mediaHtml += `
                        <div class="media-message">
                            ${replyHtml}
                            <img src="storage/app/public/${mediaItem.file_path}" class="img-fluid">
                            <span class="timestamp">${timestamp} ${statusHtml}</span>
                        </div>
                    `;
                            } else if (mediaItem.mime_type.startsWith('video/')) {
                                mediaHtml += `
                        <div class="media-message">
                            ${replyHtml}
                            <video controls>
                                <source src="storage/app/public/${mediaItem.file_path}" type="${mediaItem.mime_type}">
                            </video>
                            <span class="timestamp">${timestamp} ${statusHtml}</span>
                        </div>
                    `;
                            } else if (mediaItem.mime_type.startsWith('audio/')) {
                                const audioId = `audio-${message.id}-${mediaItem.id}`;
                                mediaHtml += `
                        <div class="media-message">
                            ${replyHtml}
                            <audio id="${audioId}" src="storage/app/public/${mediaItem.file_path}"></audio>
                            <div class="audio-player" data-audio-id="${audioId}">
                                <div class="play-btn ${playedClass}">
                                    <ion-icon name="play"></ion-icon>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar"></div>
                                </div>
                                <div class="time">0:00</div>
                                <span class="timestamp">${timestamp} ${statusHtml}</span>
                            </div>
                        </div>
                    `;
                            }
                        });
                    }

                    if (message.type === 'template') {
                        messageBodyHtml = renderTemplateMessage(message);
                    } else if (message.deleted) {
                        messageBodyHtml = `
                            <div class="message-content deleted-message">
                                <p><em>This message has been deleted</em></p>
                            </div>
                        `;
                        messageActions = '';
                    } else if (message.body && !['Photo', 'PDF file', 'Video', 'Document', 'Audio message',
                            'Attached file'
                        ]
                        .includes(message.body)) {
                        messageBodyHtml = `
                            <div class="message-content">
                                ${replyHtml}
                                <p>
                                    ${message.body}
                                    <br>
                                    <span class="timestamp"><span>${formatTime(message.created_at)}</span> ${statusHtml}</span>
                                </p>
                            </div>
                        `;
                    }

                    function renderTemplateMessage(message) {
                        let templateHtml = '';
                        const timestamp = formatTime(message.created_at);

                        const templateData = JSON.parse(message.template_data) || {};
                        const templateName = message.template_name || 'Template';

                        templateHtml = `
                            <div class="template-message-preview">
                                <div class="template-header">
                                    <span class="template-badge">Template</span>
                                    <span class="template-name">${templateName}</span>
                                </div>
                        `;

                        if (templateData.header) {
                            if (templateData.header.type === 'IMAGE' && templateData.header.image) {
                                templateHtml += `
                                    <div class="template-header-media">
                                        <img src="storage/app/public/${templateData.header.image}" class="img-fluid">
                                    </div>
                                `;
                            } else if (templateData.header.type === 'VIDEO' && templateData.header.video) {
                                templateHtml += `
                                    <div class="template-header-media">
                                        <video controls>
                                            <source src="storage/app/public/${templateData.header.video}" type="video/mp4">
                                        </video>
                                    </div>
                                `;
                            } else if (templateData.header.type === 'DOCUMENT' && templateData.header.document) {
                                templateHtml += `
                                    <div class="template-header-document">
                                        <div class="document-icon">
                                            <ion-icon name="document-outline"></ion-icon>
                                        </div>
                                        <div class="document-info">
                                            <div class="document-name">${templateData.header.document_name || 'Document'}</div>
                                            <div class="document-size">${templateData.header.document_size || ''}</div>
                                        </div>
                                    </div>
                                `;
                            } else if (templateData.header.text) {
                                templateHtml += `
                                    <div class="template-header-text">
                                        ${templateData.header.text}
                                    </div>
                                `;
                            }
                        }

                        if (templateData.body) {
                            templateHtml += `
                                <div class="template-body">
                                    ${templateData.body}
                                </div>
                            `;
                        }

                        if (templateData.footer) {
                            templateHtml += `
                                <div class="template-footer">
                                    ${templateData.footer}
                                </div>
                            `;
                        }

                        if (templateData.buttons && templateData.buttons.length > 0) {
                            templateHtml += `<div class="template-buttons">`;

                            templateData.buttons.forEach(button => {
                                let buttonClass = '';
                                let icon = '';

                                switch (button.type) {
                                    case 'URL':
                                        buttonClass = 'url-button';
                                        icon = '<ion-icon name="link-outline"></ion-icon>';
                                        break;
                                    case 'PHONE_NUMBER':
                                        buttonClass = 'phone-button';
                                        icon = '<ion-icon name="call-outline"></ion-icon>';
                                        break;
                                    case 'QUICK_REPLY':
                                        buttonClass = 'quick-reply-button';
                                        break;
                                    case 'COPY_CODE':
                                        buttonClass = 'copy-code-button';
                                        icon = '<ion-icon name="ticket-outline"></ion-icon>';
                                        break;
                                }

                                templateHtml += `
                                    <div class="template-button ${buttonClass}">
                                        ${icon} ${button.text || button.value}
                                    </div>
                                `;
                            });

                            templateHtml += `</div>`;
                        }

                        if (templateData.limited_time_offer && templateData.buttons.length > 0) {
                            templateHtml += `
                                <div class="template-limited-offer">
                                    <div class="offer-badge">
                                        <ion-icon name="time-outline"></ion-icon>
                                        Limited Time Offer
                                        <span class="offer-expiry">Expires in ${templateData.limited_time_offer.expires_in}</span>
                                    </div>
                                </div>
                            `;
                        }

                        templateHtml += `
                                <div class="template-timestamp">
                                    <span>${timestamp}</span>
                                </div>
                            </div>
                        `;

                        return templateHtml;
                    }

                    const messageHtml = `
                        <div class="message ${messageClass} ${failedClass}" 
                            data-id="${message.id}" 
                            data-message-id="${message.message_id}" 
                            data-date="${new Date(message.created_at).toDateString()}">
                            
                            ${messageActions}
                            ${mediaHtml}
                            ${messageBodyHtml}
                        </div>  
                        ${errorHtml} 
                    `;


                    $('#chatMessages').append(messageHtml);
                    initAudioPlayers($('#chatMessages .audio-player').last());
                    initMessageActions();
                }

                function scrollToBottom() {
                    const chatbox = document.getElementById('chatMessages');
                    if (chatbox) {
                        setTimeout(() => {
                            chatbox.scrollTop = chatbox.scrollHeight;
                        }, 50);
                    }
                }

                function initMessageActions() {
                    $(document).off('click', '.message-actions-btn');
                    $(document).off('click', '.message-actions-menu li.delete');

                    $(document).on('click', '.message-actions-btn', function(e) {
                        e.stopPropagation();
                        const Id = $(this).data('id');
                        $('.message-actions-menu').not(`#actionsMenu${Id}`).removeClass('show');
                        $(`#actionsMenu${Id}`).toggleClass('show');
                    });

                    $(document).on('click', '.message-actions-menu li.delete', function(e) {
                        e.stopPropagation();
                        const messageId = $(this).data('message-id');
                        deleteMessage(messageId);
                        $('.message-actions-menu').removeClass('show');
                    });

                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('.message-actions').length) {
                            $('.message-actions-menu').removeClass('show');
                        }
                    });
                }

                function deleteMessage(messageId) {
                    const accountId = $('#accountFilter').val();

                    Swal.fire({
                        title: 'Delete Message',
                        text: 'Are you sure you want to delete this message? This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading();

                            $.ajax({
                                url: '/whatsapp-template/messages/delete',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    account_id: accountId,
                                    message_id: messageId
                                },
                                success: function(response) {
                                    if (response.success) {
                                        if (response.status === 'pending') {
                                            const $messageElement = $(
                                                `.message[data-message-id="${messageId}"]`);
                                            if ($messageElement.length) {
                                                $messageElement.find('.message-content').html(
                                                    '<p><em>This message has been deleted</em></p>'
                                                );
                                                $messageElement.find('.media-message').html(
                                                    '<p><em>This media has been deleted</em></p>'
                                                );
                                                $messageElement.find('.message-actions').remove();
                                            }

                                            const lastMessage = $(
                                                '.chatlist .block.active .message_p p');
                                            if (lastMessage.text().includes(
                                                    'This message has been deleted')) {
                                                lastMessage.text('This message has been deleted');
                                            }

                                            toastr.success(response.message);
                                        } else {
                                            toastr.error(response.message);
                                        }
                                    } else {
                                        toastr.error(response.message);
                                    }
                                },
                                error: function(xhr) {
                                    toastr.error(xhr.responseJSON?.message ||
                                        'Failed to delete message');
                                },
                                complete: function() {
                                    hideLoading();
                                }
                            });
                        }
                    });
                }

                function updateUrlParams(params) {
                    const url = new URL(window.location);
                    Object.entries(params).forEach(([key, value]) => {
                        if (value) {
                            url.searchParams.set(key, value);
                        } else {
                            url.searchParams.delete(key);
                        }
                    });
                    window.history.pushState({}, '', url);
                }

                function handleUrlChange() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const accountId = urlParams.get('account_id');
                    const activeChat = urlParams.get('active_chat');

                    if (accountId && accountId !== currentActiveAccount) {
                        $('#accountFilter').val(accountId);
                        loadConversations(accountId)
                            .then(() => {
                                if (activeChat) {
                                    loadMessages(accountId, activeChat);
                                }
                            });
                    } else if (activeChat && activeChat !== currentActiveChat) {
                        loadMessages(currentActiveAccount, activeChat);
                    }
                }

                window.addEventListener('popstate', function() {
                    handleUrlChange();
                });

                $(document).on('change', '#accountFilter', function() {
                    const accountId = $(this).val();
                    if (accountId) {
                        currentActiveAccount = accountId;
                        loadConversations(accountId);
                        updateUrlParams({
                            account_id: accountId,
                            active_chat: null
                        });
                    }
                });

                $(document).on('click', '.chatlist .block', function() {
                    const conversationId = $(this).data('id');
                    const contactNumber = $(this).data('contact');
                    const accountId = $('#accountFilter').val();

                    if ($(this).hasClass('unread')) {
                        markAsRead(conversationId);
                        $(this).removeClass('unread').find('.message_p b').remove();
                        decrementUnreadBadge();
                    }

                    clearReply();

                    loadMessages(accountId, conversationId);
                    loadContactDetails(contactNumber);
                });

                function markAsRead(conversationId) {
                    $.ajax({
                        url: `/whatsapp/conversations/${conversationId}/mark-as-read`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log('Conversation marked as read');
                            }
                        }
                    });
                }

                $('#sendMessageForm').submit(function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    const hasText = $('#messageInput').val().trim() !== '';
                    const hasAudio = audioBlob !== null;
                    const hasMedia = $('#mediaInput')[0].files.length > 0;
                    console.log(hasAudio)
                    console.log(audioBlob)

                    if (!hasText && !hasAudio && !hasMedia) {
                        toastr.error('Please enter a message or record audio');
                        return;
                    }

                    if (hasAudio) {
                        submitAudioMessage(formData);
                    } else {
                        submitRegularMessage(formData);
                    }

                    if (hasMedia) {
                        $('#attachMedia').prop('disabled', true).html(
                            '<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>');
                        $('#attachMedia').prop('disabled', true);
                        $('#recordBtn').prop('disabled', true);
                    }

                });

                function submitAudioMessage(formData) {
                    stopRecording();
                    const audioFile = new File([audioBlob], 'recording.mp3', {
                        type: 'audio/mp3',
                        lastModified: Date.now()
                    });
                    formData.append('audio', audioFile);

                    if (currentQuotedMessage) {
                        formData.append('quoted_message_id', currentQuotedMessage);
                    }

                    const sendBtn = $('#sendBtn');
                    const originalIcon = sendBtn.html();
                    sendBtn.html('<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>');
                    sendBtn.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('whatsapp-template.send-message') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                resetInputUI();
                                appendMessage(response.message);
                                updateConversationList(response.message, response.message.contact_number,
                                    response.message.contact_name);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to send message');
                        },
                        complete: function() {
                            sendBtn.html(originalIcon);
                            sendBtn.prop('disabled', false);
                            clearReply();
                            audioBlob = null;
                        }
                    });
                }

                function submitRegularMessage(formData) {
                    let hasMedia = false;

                    if (currentQuotedMessage) {
                        formData.append('quoted_message_id', currentQuotedMessage);
                    }

                    if ($('#messageInput').val().trim()) {
                        formData.append('message', $('#messageInput').val().trim());
                    }

                    if ($('#mediaInput')[0].files.length > 0) {
                        hasMedia = true;
                        const mediaFile = $('#mediaInput')[0].files[0];
                        formData.append('media', mediaFile);
                        console.log(hasMedia);
                    }

                    const sendBtn = $('#sendBtn');
                    const originalIcon = sendBtn.html();
                    const originalMediaIcon = $("#attachMedia").html();
                    sendBtn.html('<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>');
                    sendBtn.prop('disabled', true);

                    $.ajax({
                        url: '{{ route('whatsapp-template.send-message') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                $('#messageInput').val('');
                                $('#mediaInput').val('');
                                resetInputUI();
                                appendMessage(response.message);
                                updateConversationList(response.message, response.message.contact_number,
                                    response.message.contact_name);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to send message');
                        },
                        complete: function() {
                            sendBtn.html(originalIcon);
                            sendBtn.prop('disabled', false);
                            $('#recordBtn').prop('disabled', false);
                            $("#attachMedia").html(originalMediaIcon);
                            $('#attachMedia').prop('disabled', false);
                            clearReply();
                        }
                    });
                }

                $('#attachMedia').click(function() {
                    $('#mediaInput').click();
                });

                $('#mediaInput').change(function() {
                    if (this.files.length > 0) {
                        $('#sendBtn').prop('disabled', true).html(
                            '<span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>');
                        $('#attachMedia').prop('disabled', true);

                        $('#sendMessageForm').submit();
                    }
                });


                function startRecording() {
                    stopRecording();
                    audioBlob = null;
                    audioChunks = [];

                    isRecording = true;
                    navigator.mediaDevices.getUserMedia({
                            audio: true
                        })
                        .then(stream => {
                            mediaRecorder = new MediaRecorder(stream);
                            audioChunks = [];

                            mediaRecorder.ondataavailable = event => {
                                audioChunks.push(event.data);
                            };

                            mediaRecorder.onstop = () => {
                                if (audioChunks.length > 0) {
                                    audioBlob = new Blob(audioChunks, {
                                        type: 'audio/mp3'
                                    });
                                }
                                isRecording = false;

                                stream.getTracks().forEach(track => track.stop());
                            };

                            mediaRecorder.start();
                            recordingStartTime = Date.now();
                            updateRecordingTimer();

                            $('.text-input').hide();
                            $('.audio-recording-ui').show();
                            $('#recordBtn').hide();
                            $('#sendBtn').show();
                        })
                        .catch(err => {
                            isRecording = false;
                            console.error('Error accessing microphone:', err);
                            toastr.error('Microphone access denied or not available');
                        });
                }

                function stopRecording() {
                    if (isRecording && mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                        clearInterval(recordingTimer);
                        isRecording = false;

                        if (mediaRecorder.stream) {
                            mediaRecorder.stream.getTracks().forEach(track => track.stop());
                        }
                    }
                    audioChunks = [];
                }

                function cancelRecording() {
                    stopRecording();
                    audioChunks = [];
                    audioBlob = null;
                    resetInputUI();
                }

                function updateRecordingTimer() {
                    recordingTimer = setInterval(() => {
                        const seconds = Math.floor((Date.now() - recordingStartTime) / 1000);
                        const minutes = Math.floor(seconds / 60);
                        const remainingSeconds = seconds % 60;
                        $('.recording-timer').text(
                            `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`);
                    }, 1000);
                }

                function resetInputUI() {
                    stopRecording();

                    $('.text-input').show();
                    $('.audio-recording-ui').hide();
                    $('.text-input').val('');
                    $('.recording-timer').text('0:00');

                    audioChunks = [];
                    audioBlob = null;
                    isRecording = false;

                    if ($('#messageInput').val().trim() === '') {
                        $('#recordBtn').show();
                        $('#sendBtn').hide();
                    } else {
                        $('#recordBtn').hide();
                        $('#sendBtn').show();
                    }
                }

                $('#messageInput').on('input', function() {
                    if ($(this).val().trim() !== '') {
                        $('#recordBtn').hide();
                        $('#sendBtn').show();
                    } else {
                        $('#recordBtn').show();
                        $('#sendBtn').hide();
                    }
                });

                $('#recordBtn').on('click', function() {
                    startRecording();
                });

                $('.cancel-recording-btn').on('click', function() {
                    cancelRecording();
                });

                $('#sendBtn').on('click', function() {
                    stopRecording();
                    $('#sendMessageForm').submit();
                });

                function initAudioPlayers(element = null) {
                    const players = element ? $(element) : $('.audio-player');

                    players.each(function() {
                        const player = $(this);
                        const audioId = player.data('audio-id');
                        const audio = document.getElementById(audioId);
                        const playBtn = player.find('.play-btn');
                        const progressBar = player.find('.progress-bar');
                        const timeDisplay = player.find('.time');

                        if (player.data('initialized')) return;
                        player.data('initialized', true);

                        const formatTime = (seconds) => {
                            const minutes = Math.floor(seconds / 60);
                            const secs = Math.floor(seconds % 60);
                            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
                        };

                        audio.addEventListener('loadedmetadata', function() {
                            timeDisplay.text(formatTime(audio.duration));
                        });

                        playBtn.on('click', function() {
                            if (audio.paused) {
                                audio.play();
                                playBtn.html('<ion-icon name="pause"></ion-icon>');
                            } else {
                                audio.pause();
                                playBtn.html('<ion-icon name="play"></ion-icon>');
                            }
                        });

                        audio.addEventListener('timeupdate', function() {
                            const currentTime = audio.currentTime;
                            const duration = audio.duration || 1;

                            const progressPercent = (currentTime / duration) * 100;
                            progressBar.css('width', progressPercent + '%');

                            timeDisplay.text(formatTime(currentTime));
                        });

                        player.find('.progress-container').on('click', function(e) {
                            const width = $(this).width();
                            const clickX = e.offsetX;
                            const duration = audio.duration;
                            audio.currentTime = (clickX / width) * duration;
                        });

                        audio.addEventListener('ended', function() {
                            playBtn.html('<ion-icon name="play"></ion-icon>');
                            progressBar.css('width', '0%');
                            timeDisplay.text(formatTime(audio.duration));
                        });
                    });
                }

                function updateBlockUI(isBlocked) {
                    if (isBlocked) {
                        $('.block-contact').hide();
                        $('.unblock-contact').show();
                        $('#messageInput').prop('disabled', true).attr('placeholder',
                            'This chat is blocked. You cannot send messages.');
                        $('#sendBtn, #recordBtn, #attachMedia').hide();
                    } else {
                        $('.block-contact').show();
                        $('.unblock-contact').hide();
                        $('#messageInput').prop('disabled', false).attr('placeholder', 'Type a message');
                        $('#recordBtn').show();
                    }
                }

                $(document).on('click', '.block-contact', function(e) {
                    e.preventDefault();
                    if (!currentActiveChat) return;

                    Swal.fire({
                        title: 'Block Contact',
                        text: 'Are you sure you want to block this contact? You won\'t be able to send messages to them.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, block it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/whatsapp-template/conversations/' + currentActiveChat +
                                    '/block',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    account_id: currentActiveAccount
                                },
                                success: function() {
                                    toastr.success('Contact blocked successfully');
                                    updateBlockUI(true);
                                },
                                error: function() {
                                    toastr.error('Failed to block contact');
                                }
                            });
                        }
                    });
                });

                $(document).on('click', '.unblock-contact', function(e) {
                    e.preventDefault();
                    if (!currentActiveChat) return;

                    Swal.fire({
                        title: 'Unblock Contact',
                        text: 'Are you sure you want to unblock this contact?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, unblock it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/whatsapp-template/conversations/' + currentActiveChat +
                                    '/unblock',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    account_id: currentActiveAccount
                                },
                                success: function() {
                                    toastr.success('Contact unblocked successfully');
                                    updateBlockUI(false);
                                },
                                error: function() {
                                    toastr.error('Failed to unblock contact');
                                }
                            });
                        }
                    });
                });

                function initQuickFilters() {
                    $('.btn-filter').on('click', function() {
                        const filterType = $(this).data('filter');

                        $('.btn-filter').removeClass('active');
                        $(this).addClass('active');

                        currentQuickFilter = filterType;
                        currentLabelFilter = 'all';

                        $('.label-filter-badge').removeClass('active');
                        $('.label-filter-badge[data-label-id="all"]').addClass('active');

                        loadConversationsByFilter(filterType);
                    });
                }



                function loadLabelFilters(accountId) {
                    if (!accountId) return;

                    $.ajax({
                        url: '/whatsapp-template/labels',
                        method: 'GET',
                        data: {
                            account_id: accountId,
                            with_counts: 1
                        },
                        success: function(response) {
                            if (response.success) {
                                renderLabelFilters(response.labels);
                            }
                        }
                    });
                }

                function renderLabelFilters(labels) {
                    const $labelFilters = $('#labelFilters');
                    $labelFilters.empty();

                    const allLabelsHtml = `
                        <div class="label-filter-badge ${currentLabelFilter === 'all' ? 'active' : ''}" data-label-id="all">
                            All Conversations
                        </div>
                    `;
                    $labelFilters.append(allLabelsHtml);

                    labels.forEach(label => {
                        const isActive = currentLabelFilter === label.id.toString();
                        const labelHtml = `
                            <div class="label-filter-badge ${isActive ? 'active' : ''}" data-label-id="${label.id}">
                                <span class="label-color-dot" style="background-color: ${label.color}"></span>
                                ${label.name}
                                <span class="label-count">${label.conversations_count || 0}</span>
                            </div>
                        `;
                        $labelFilters.append(labelHtml);
                    });

                    $('.label-filter-badge').off('click').on('click', function() {
                        const labelId = $(this).data('label-id');

                        $('.label-filter-badge').removeClass('active');
                        $(this).addClass('active');
                        $('.btn-filter').removeClass('active');
                        $('.btn-filter[data-filter="all"]').addClass('active');

                        filterConversationsByLabel(labelId);
                    });

                    updateCarousel();
                }

                $('#accountFilter').on('change', function() {
                    const accountId = $(this).val();
                    if (accountId) {
                        currentQuickFilter = 'all';
                        currentLabelFilter = 'all';
                        $('.btn-filter').removeClass('active');
                        $('.btn-filter[data-filter="all"]').addClass('active');
                        $('.label-filter-badge').removeClass('active');
                        $('.label-filter-badge[data-label-id="all"]').addClass('active');

                        loadConversationsByFilter('all');
                    }
                });
                initQuickFilters();

                function loadLabelsModal(conversationId = null) {
                    const accountId = $('#accountFilter').val();
                    if (!accountId) return;

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/labels',
                        method: 'GET',
                        data: {
                            account_id: accountId,
                            with_counts: true,
                            conversation_id: conversationId
                        },
                        success: function(response) {
                            if (response.success) {
                                renderLabelsModal(response.labels, conversationId);
                                $('#labelsModal').modal('show');
                            }
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function renderLabelsModal(labels, conversationId = null) {
                    const $container = $('#labelsContainer');
                    $container.empty();

                    if (labels.length === 0) {
                        $container.html('<p class="text-muted">No labels created yet</p>');
                        return;
                    }

                    labels.forEach(label => {
                        const $label = $(`
                            <div class="col-md-4">
                                <div class="label-card">
                                    <div class="form-check-labels">
                                        <label class="form-check-label" style="align-items: center;display: flex;" for="label-${label.id}">
                                            <span class="label-color" style="background-color: ${label.color}"></span>
                                            ${label.name}
                                            <span class="label-count">${label.conversations_count}</span>
                                        </label>
                                        <div style="display:flex;gap: 5px;align-items: center;">
                                            <input class="form-check-input label-checkbox" type="checkbox" 
                                            id="label-${label.id}" data-id="${label.id}"
                                            ${label.assigned ? 'checked' : ''}>
                                            <button class="btn btn-sm btn-outline-danger delete-label-btn" 
                                                data-id="${label.id}" title="Delete label">
                                            <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                        $container.append($label);
                    });
                }

                function loadConversationsByFilter(filterType) {
                    const accountId = $('#accountFilter').val();

                    if (!accountId) {
                        toastr.error('Please select an account first');
                        return;
                    }

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/labels/conversations',
                        method: 'GET',
                        data: {
                            account_id: accountId,
                            label_id: null,
                            filter: filterType
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#chatList').empty();

                                // Update unread badge count based on the filter
                                if (filterType === 'unread') {
                                    // For unread filter, show the count of filtered conversations
                                    updateUnreadBadge(response.conversations?.length || 0);
                                } else {
                                    // For all filter, show count of conversations with unread messages
                                    const unreadConversationsCount = countUnreadConversations(response
                                        .conversations || []);
                                    updateUnreadBadge(unreadConversationsCount);
                                }

                                renderLabelFilters(response.labels || []);

                                if (response.conversations && response.conversations.length > 0) {
                                    response.conversations.forEach(conversation => {
                                        const latestMessage = conversation.latest_message || {};

                                        const mockMessage = {
                                            whats_app_conversation_id: conversation.id,
                                            contact_name: conversation.contact_name,
                                            contact_number: conversation.contact_number,
                                            created_at: latestMessage.created_at || conversation
                                                .last_message_at,
                                            body: latestMessage.body || '',
                                            deleted: latestMessage.deleted || false,
                                            from: latestMessage.from || conversation
                                                .contact_number,
                                            contact_avatar: conversation.contact_avatar,
                                            status: latestMessage.status,
                                            direction: latestMessage.direction,
                                            message_id: latestMessage.message_id,
                                            unread_count: conversation.unread_count || 0
                                        };
                                        updateConversationList(mockMessage, conversation
                                            .contact_number, conversation.contact_name);
                                    });
                                } else {
                                    $('#chatList').html(
                                        '<div class="no-conversations">No conversations found</div>');
                                }
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to load conversations');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                // Update the filterConversationsByLabel function
                function filterConversationsByLabel(labelId) {
                    const accountId = $('#accountFilter').val();

                    if (!accountId) {
                        toastr.error('Please select an account first');
                        return;
                    }

                    currentLabelFilter = labelId;
                    currentQuickFilter = 'all';

                    $('.btn-filter').removeClass('active');
                    $('.btn-filter[data-filter="all"]').addClass('active');

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/labels/conversations',
                        method: 'GET',
                        data: {
                            account_id: accountId,
                            label_id: labelId === 'all' ? null : labelId,
                            filter: 'all'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#chatList').empty();

                                // Update unread badge with conversation count
                                const unreadConversationsCount = countUnreadConversations(response
                                    .conversations || []);
                                updateUnreadBadge(unreadConversationsCount);

                                renderLabelFilters(response.labels || []);

                                if (response.conversations && response.conversations.length > 0) {
                                    response.conversations.forEach(conversation => {
                                        const latestMessage = conversation.latest_message || {};

                                        const mockMessage = {
                                            whats_app_conversation_id: conversation.id,
                                            contact_name: conversation.contact_name,
                                            contact_number: conversation.contact_number,
                                            created_at: latestMessage.created_at || conversation
                                                .last_message_at,
                                            body: latestMessage.body || '',
                                            deleted: latestMessage.deleted || false,
                                            from: latestMessage.from || conversation
                                                .contact_number,
                                            contact_avatar: conversation.contact_avatar,
                                            status: latestMessage.status,
                                            direction: latestMessage.direction,
                                            message_id: latestMessage.message_id,
                                            unread_count: conversation.unread_count || 0
                                        };
                                        updateConversationList(mockMessage, conversation
                                            .contact_number, conversation.contact_name);
                                    });
                                } else {
                                    $('#chatList').html(
                                        '<div class="no-conversations">No conversations found for this label</div>'
                                    );
                                }
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to filter conversations');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function countUnreadConversations(conversations) {
                    if (!conversations || !Array.isArray(conversations)) return 0;

                    return conversations.filter(conversation => {
                        return conversation.unread_count > 0;
                    }).length;
                }

                function updateUnreadBadge(count) {
                    const $unreadBadge = $('#unreadCountBadge');
                    if (count > 0) {
                        $unreadBadge.text(count).show();
                    } else {
                        $unreadBadge.hide();
                    }
                }

                function calculateTotalUnreadConversations() {
                    let unreadConversations = 0;
                    $('#chatList .block').each(function() {
                        if ($(this).hasClass('unread')) {
                            unreadConversations++;
                        }
                    });
                    return unreadConversations;
                }


                function saveLabelAssignments(conversationId) {
                    const accountId = $('#accountFilter').val();
                    const checkedLabels = [];

                    $('.label-checkbox:checked').each(function() {
                        checkedLabels.push($(this).data('id'));
                    });

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/conversations/' + conversationId + '/labels/assign',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            label_ids: checkedLabels
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Label assignments updated successfully');
                                loadLabelFilters(accountId);
                                if (currentLabelFilter) {
                                    filterConversationsByLabel(currentLabelFilter);
                                }
                            }
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function createNewLabel() {
                    const accountId = $('#accountFilter').val();
                    const name = $('#newLabelName').val().trim();
                    const color = $('#newLabelColor').val();

                    if (!name) {
                        toastr.error('Please enter a label name');
                        return;
                    }

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/labels',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            account_id: accountId,
                            name: name,
                            color: color
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#newLabelName').val('');
                                toastr.success('Label created successfully');
                                loadLabelFilters(accountId);
                                if (currentActiveChat) {
                                    loadLabelsModal(currentActiveChat);
                                } else {
                                    loadLabelsModal();
                                }
                            }
                        },
                        complete: function() {
                            hideLoading();
                            $("#labelsModal").dismiss();

                        }
                    });
                }

                function deleteLabel(labelId) {
                    Swal.fire({
                        title: 'Delete Label',
                        text: 'Are you sure you want to delete this label? This will remove it from all conversations.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading();

                            $.ajax({
                                url: '/whatsapp-template/labels/' + labelId,
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        toastr.success('Label deleted successfully');
                                        const accountId = $('#accountFilter').val();
                                        loadLabelFilters(accountId);
                                        if (currentActiveChat) {
                                            loadLabelsModal(currentActiveChat);
                                        } else {
                                            loadLabelsModal();
                                        }
                                    }
                                },
                                complete: function() {
                                    hideLoading();
                                }
                            });
                        }
                    });
                }

                $(document).on('change', '#accountFilter', function() {
                    const accountId = $(this).val();
                    if (accountId) {
                        currentActiveAccount = accountId;
                        currentLabelFilter = null;
                        loadLabelFilters(accountId);
                        loadConversations(accountId);
                        updateUrlParams({
                            account_id: accountId,
                            active_chat: null
                        });
                    }
                });

                $(document).on('click', '#manageLabelsBtn', function() {
                    loadLabelsModal(currentActiveChat);
                });

                $(document).on('click', '#saveLabelsBtn', function() {
                    if (currentActiveChat) {
                        saveLabelAssignments(currentActiveChat);
                    } else {
                        $('#labelsModal').modal('hide');
                    }
                });

                $(document).on('click', '#addLabelBtn', function() {
                    createNewLabel();
                });

                $(document).on('click', '.delete-label-btn', function(e) {
                    e.stopPropagation();
                    const labelId = $(this).data('id');
                    deleteLabel(labelId);
                });

                $(document).on('click', '.label-filter-badge', function() {
                    const labelId = $(this).data('label-id');
                    filterConversationsByLabel(labelId);
                });

                let rowCount = 0;

                $(document).on('click', '#createLeadBtn', function() {
                    if (!currentActiveChat) {
                        toastr.error('Please select a conversation first');
                        return;
                    }

                    const contactName = $('#activeChatName').text();
                    const contactNumber = $('.chatlist .block.active').data('contact');

                    $('#lead_name').val(contactName);
                    $('#lead_mobile').val(contactNumber);
                    $('#whatsapp_contact_name').val(contactName);
                    $('#whatsapp_contact_number').val(contactNumber);

                    $('#whatsappLeadModal').modal('show');
                });

                $('#add_row').click(function(e) {
                    e.preventDefault();
                    rowCount++;
                    let row = `
                    <tr id='addr${rowCount}'>
                        <td>
                            <select class="form-control product_lead" name="product_lead">
                                <option value="">Select Product</option>
                                @foreach ($proo as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" class="form-control lead_quantity" placeholder="Quantity"></td>
                        <td><input type="number" class="form-control price_lead" placeholder="Price" readonly></td>
                        <td><button class="btn btn-danger row-remove">×</button></td>
                    </tr>`;
                    $('#tab_logic tbody').append(row);
                });

                $(document).on('click', '.row-remove', function(e) {
                    e.preventDefault();
                    const rowId = $(this).closest('tr').attr('id');
                    if (rowId === 'addr0') {
                        toastr.error('Cannot delete the first row');
                        return;
                    }
                    $(this).closest('tr').remove();
                });

                $(document).on('change', '.product_lead', function() {
                    const price = $(this).find(':selected').data('price') || 0;
                    $(this).closest('tr').find('.price_lead').val(price);
                });

                $(document).on('click', '#saveWhatsappLead', function() {
                    const btn = $(this);
                    btn.prop('disabled', true);
                    const dateDelivered = $('#lead_date_delivered').val() || null;
                    const leadData = {
                        _token: '{{ csrf_token() }}',
                        name_customer: $('#lead_name').val(),
                        mobile: $('#lead_mobile').val(),
                        mobile2: $('#lead_mobile2').val(),
                        id_city: $('#lead_city').val(),
                        address: $('#lead_address').val(),
                        market: 'whatsapp',
                        whatsapp_contact_name: $('#whatsapp_contact_name').val(),
                        whatsapp_contact_number: $('#whatsapp_contact_number').val()
                    };

                    const products = [];
                    $('#tab_logic tbody tr').each(function() {
                        const productId = $(this).find('.product_lead').val();
                        const quantity = $(this).find('.lead_quantity').val();
                        const price = $(this).find('.price_lead').val();

                        if (productId && quantity && price) {
                            products.push({
                                id_product: productId,
                                quantity,
                                price,
                                date_delivered: dateDelivered
                            });
                        }
                    });

                    if (!leadData.name_customer || !leadData.mobile || !leadData.id_city || products.length ===
                        0) {
                        toastr.error('Please fill all required fields and add at least one product');
                        btn.prop('disabled', false);
                        return;
                    }

                    leadData.id_product = products[0].id_product;
                    leadData.total = products.reduce((sum, p) => sum + p.quantity * p.price, 0);

                    $.ajax({
                        url: '{{ route('whatsapp-template.storeLead') }}',
                        method: 'POST',
                        data: {
                            lead: leadData,
                            products: products
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Lead created successfully');
                                $('#whatsappLeadModal').modal('hide');
                            } else {
                                toastr.error(response.message || 'Failed to create lead');
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Failed to create lead');
                        },
                        complete: function() {
                            btn.prop('disabled', false);
                        }
                    });
                });


                let selectedTemplate = null;
                let templates = [];

                $('#templateBtn').on('click', function() {
                    loadTemplates();
                    new bootstrap.Offcanvas(document.getElementById('templateOffcanvas')).show();
                });

                function loadTemplates() {
                    const accountId = $('#accountFilter').val();
                    if (!accountId) {
                        toastr.error('Please select an account first');
                        return;
                    }

                    $('#templatesList').html(`
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading templates...</span>
                            </div>
                            <p class="mt-2">Loading templates...</p>
                        </div>
                    `);

                    $.ajax({
                        url: '/whatsapp-template/templates',
                        method: 'GET',
                        data: {
                            account_id: accountId,
                            status: 'approved'
                        },
                        success: function(response) {
                            if (response.success && response.templates.length > 0) {
                                templates = response.templates;
                                renderTemplatesList(templates);
                            } else {
                                $('#templatesList').html(`
                            <div class="text-center py-4">
                                <p>No approved templates found</p>
                                <a href="/whatsapp-templates/create/${accountId}" class="btn btn-sm btn-primary mt-2">
                                    Create New Template
                                </a>
                            </div>
                        `);
                            }
                        },
                        error: function() {
                            $('#templatesList').html(`
                        <div class="text-center py-4">
                            <p class="text-danger">Failed to load templates</p>
                        </div>
                    `);
                        }
                    });
                }

                // Render templates list
                function renderTemplatesList(templatesList) {
                    let html = '';

                    templatesList.forEach(template => {
                        html += `
                    <div class="template-card" data-template-id="${template.id}">
                        <div class="template-name">${template.name}</div>
                        <div class="template-category">${template.category}</div>
                        <div class="template-body-preview">${template.body || 'No body content'}</div>
                        <div class="template-meta">
                            <span>${template.language}</span>
                            <span>${new Date(template.created_at).toLocaleDateString()}</span>
                        </div>
                    </div>
                `;
                    });

                    $('#templatesList').html(html);


                    $('.template-card').on('click', function() {
                        const templateId = $(this).data('template-id');
                        selectedTemplate = templates.find(t => t.id == templateId);

                        if (selectedTemplate) {
                            showTemplatePreview(selectedTemplate);
                        }
                    });
                }


                $('#templateSearch, #templateLanguageFilter').on('input change', function() {
                    const searchTerm = $('#templateSearch').val().toLowerCase();
                    const languageFilter = $('#templateLanguageFilter').val();

                    let filteredTemplates = templates;

                    if (searchTerm) {
                        filteredTemplates = filteredTemplates.filter(t =>
                            t.name.toLowerCase().includes(searchTerm) ||
                            (t.body && t.body.toLowerCase().includes(searchTerm))
                        );
                    }

                    if (languageFilter) {
                        filteredTemplates = filteredTemplates.filter(t => t.language === languageFilter);
                    }

                    renderTemplatesList(filteredTemplates);
                });

                function extractVariablesFromTemplate(template) {
                    const variables = {
                        header: [],
                        body: [],
                        limited_time_offer: [],
                        buttons: []
                    };

                    try {
                        if (!template || !template.components) return variables;

                        let components = template.components;
                        if (typeof components === 'string') {
                            try {
                                components = JSON.parse(components);
                            } catch (e) {
                                console.error('Failed to parse template components:', e);
                                return variables;
                            }
                        }

                        if (!Array.isArray(components)) return variables;

                        components.forEach(component => {
                            if (!component || !component.type) return;

                            const type = component.type.toUpperCase();

                            // HEADER
                            if (type === 'HEADER') {
                                if (component.text) {
                                    extractVariablesFromText(component.text, variables.header, 'Header');
                                } else if (component.example?.header_handle) {
                                    component.example.header_handle.forEach((_, index) => {
                                        variables.header.push({
                                            name: index + 1,
                                            displayName: `Header Variable ${index + 1}`,
                                            placeholder: `Enter value for header variable ${index + 1}`,
                                            type: 'text'
                                        });
                                    });
                                }
                            }

                            // BODY
                            if (type === 'BODY') {
                                if (component.text) {
                                    extractVariablesFromText(component.text, variables.body, 'Body');
                                } else if (component.example?.body_text) {
                                    component.example.body_text.forEach(exampleGroup => {
                                        if (Array.isArray(exampleGroup)) {
                                            exampleGroup.forEach((_, index) => {
                                                variables.body.push({
                                                    name: index + 1,
                                                    displayName: `Body Variable ${index + 1}`,
                                                    placeholder: `Enter value for body variable ${index + 1}`,
                                                    type: 'text'
                                                });
                                            });
                                        } else if (typeof exampleGroup === 'object') {
                                            Object.keys(exampleGroup).forEach((key, index) => {
                                                const varName = key.replace(/[{}]/g, '');
                                                variables.body.push({
                                                    name: varName,
                                                    displayName: `Body Variable ${index + 1}`,
                                                    placeholder: `Enter value for ${varName}`,
                                                    type: 'text'
                                                });
                                            });
                                        }
                                    });
                                }
                            }

                            // LIMITED TIME OFFER
                            if (type === 'LIMITED_TIME_OFFER' || type === 'limited_time_offer') {
                                variables.limited_time_offer.push({
                                    name: 'expiration_time_ms',
                                    displayName: 'Expiration Time',
                                    placeholder: 'Enter expiration time in milliseconds',
                                    type: 'number',
                                    required: true
                                });
                            }

                            // BUTTONS
                            if (type === 'BUTTONS' && component.buttons) {
                                component.buttons.forEach((button, index) => {
                                    const buttonType = button.type?.toUpperCase();
                                    let buttonVariable = {
                                        name: `button_${index}`,
                                        displayName: `Button ${index + 1} (${buttonType})`,
                                        placeholder: `Enter value for ${buttonType} button`,
                                        type: 'text',
                                        buttonType: buttonType,
                                        buttonIndex: index
                                    };

                                    if (buttonType === 'URL') {
                                        buttonVariable.placeholder = 'Enter URL';
                                    } else if (buttonType === 'COPY_CODE' || buttonType ===
                                        'copy_code') {
                                        buttonVariable.placeholder = 'Enter coupon code';
                                    } else if (buttonType === 'PHONE_NUMBER' || buttonType ===
                                        'phone_number') {
                                        buttonVariable.placeholder = 'Enter phone number';
                                    }

                                    variables.buttons.push(buttonVariable);
                                });
                            }
                        });
                    } catch (error) {
                        console.error('Error extracting variables from template:', error, template);
                    }
                    return variables;
                }

                function extractVariablesFromText(text, variablesArray, type) {
                    const regex = /\{\{\s*([^}]+?)\s*\}\}/g;
                    let match;
                    let index = variablesArray.length + 1;

                    while ((match = regex.exec(text)) !== null) {
                        const varName = match[1].trim();
                        variablesArray.push({
                            name: varName,
                            displayName: `${type} Variable ${index}`,
                            placeholder: `Enter value for ${type.toLowerCase()} variable ${index}`,
                            type: 'text'
                        });
                        index++;
                    }
                }



                function getHeaderFormat(template) {
                    if (!template || !template.components) return null;

                    try {
                        let components = template.components;
                        if (typeof components === 'string') {
                            components = JSON.parse(components);
                        }

                        const headerComponent = components.find(comp =>
                            comp && (comp.type === 'HEADER' || comp.type === 'header')
                        );

                        return headerComponent ? headerComponent.format : null;
                    } catch (e) {
                        console.error('Error getting header format:', e);
                        return null;
                    }
                }

                function hasFooter(template) {
                    if (!template) return false;

                    try {
                        return template.footer !== null ? true : false
                    } catch (e) {
                        console.error('Error checking footer:', e);
                        return false;
                    }
                }

                function showTemplatePreview(template) {
                    if (!template) {
                        toastr.error('Invalid template data');
                        return;
                    }


                    $('#selectedTemplateName').val(template.name || '');
                    $('#selectedTemplateLanguage').val(template.language || 'en_US');

                    const variables = extractVariablesFromTemplate(template);
                    const headerFormat = getHeaderFormat(template);
                    const hasFooterComponent = hasFooter(template);

                    console.log('Template variables:', variables);
                    console.log('Header format:', headerFormat);
                    console.log('Has footer:', hasFooterComponent);

                    let previewHtml = '';

                    if (headerFormat) {
                        if (headerFormat === 'IMAGE') {
                            previewHtml += `
                    <div class="header">
                        <div class="header-image-preview">
                            <img src="{{ asset('public/assets/images/whatsapp/default-image.png') }}" 
                                alt="Header image" class="img-fluid rounded">
                            <small class="text-muted">Image header</small>
                        </div>
                    </div>
                `;
                        } else if (headerFormat === 'VIDEO') {
                            previewHtml += `
                    <div class="header">
                        <div class="header-video-preview">
                            <div class="video-placeholder bg-light rounded p-3 text-center">
                                <ion-icon name="videocam-outline" class="fs-1"></ion-icon>
                                <p class="mt-2 mb-0">Video header</p>
                            </div>
                        </div>
                    </div>
                `;
                        } else if (headerFormat === 'DOCUMENT') {
                            previewHtml += `
                    <div class="header">
                        <div class="header-document-preview">
                            <div class="document-placeholder bg-light rounded p-3">
                                <div class="d-flex align-items-center">
                                    <ion-icon name="document-outline" class="fs-3 me-2"></ion-icon>
                                    <div>
                                        <p class="mb-0">Document.pdf</p>
                                        <small class="text-muted">Document header</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        } else if (headerFormat === 'TEXT' && template.header) {
                            let previewHeader = template.header;
                            safeForEach(variables.header, (variable, index) => {
                                previewHeader = previewHeader.replace(/\{\{.*?\}\}/,
                                    `<strong>[Header ${index + 1}]</strong>`);
                            });
                            previewHtml += `<div class="header">${previewHeader}</div>`;
                        }
                    }

                    if (template.body) {
                        let previewBody = template.body;
                        safeForEach(variables.body, (variable, index) => {
                            previewBody = previewBody.replace(/\{\{.*?\}\}/,
                                `<strong>[Variable ${index + 1}]</strong>`);
                        });
                        previewHtml += `<div class="content">${previewBody}</div>`;
                    }

                    if (hasFooterComponent && template.footer) {
                        previewHtml += `<div class="footer">${template.footer}</div>`;
                    }

                    if (variables.limited_time_offer.length > 0) {
                        previewHtml += `
                <div class="limited-time-offer">
                    <div class="offer-badge">
                        <ion-icon name="time-outline"></ion-icon>
                        Limited Time Offer
                    </div>
                </div>
            `;
                    }

                    if (variables.buttons.length > 0) {
                        let buttonsHtml = '<div class="buttons">';

                        variables.buttons.forEach(button => {
                            let buttonClass = '';
                            let icon = '';

                            switch (button.buttonType) {
                                case 'URL':
                                    buttonClass = 'url-button';
                                    icon = '<ion-icon name="link-outline"></ion-icon> ';
                                    break;
                                case 'COPY_CODE':
                                case 'copy_code':
                                    buttonClass = 'copy-code-button';
                                    icon = '<ion-icon name="ticket-outline"></ion-icon> ';
                                    break;
                                case 'PHONE_NUMBER':
                                case 'phone_number':
                                    buttonClass = 'phone-button';
                                    icon = '<ion-icon name="call-outline"></ion-icon> ';
                                    break;
                                case 'QUICK_REPLY':
                                case 'quick_reply':
                                    buttonClass = 'quick-reply-button';
                                    break;
                            }

                            buttonsHtml +=
                                `<div class="button ${buttonClass}">${icon}${button.displayName.replace(/.*\(([^)]+)\).*/, '$1')}</div>`;
                        });

                        buttonsHtml += '</div>';
                        previewHtml += buttonsHtml;
                    }

                    $('#templatePreviewContent').html(previewHtml || '<p>No preview available</p>');

                    let variablesHtml = '';

                    if (headerFormat === 'TEXT' && variables.header && variables.header.length > 0) {
                        variablesHtml += `<h6 class="mt-3">Header Variables</h6>`;
                        safeForEach(variables.header, (variable, index) => {
                            variablesHtml += `
                    <div class="variable-input-group">
                        <label class="variable-label">${variable.displayName}</label>
                        <input type="text" class="form-control" name="header_var_${variable.name}" 
                            placeholder="${variable.placeholder}" ${variable.required ? 'required' : ''}>
                        <small class="variable-helper">Parameter: ${variable.name}</small>
                    </div>
                `;
                        });
                    }

                    if (headerFormat && headerFormat !== 'TEXT') {
                        $('#mediaPreviewContainer').show();
                        setupMediaPreview(headerFormat);
                    } else {
                        $('#mediaPreviewContainer').hide();
                    }


                    if (variables.body && variables.body.length > 0) {
                        variablesHtml += `<h6 class="mt-3">Body Variables</h6>`;
                        safeForEach(variables.body, (variable, index) => {
                            variablesHtml += `
                    <div class="variable-input-group">
                        <label class="variable-label">${variable.displayName}</label>
                        <input type="text" class="form-control" name="body_var_${variable.name}" 
                            placeholder="${variable.placeholder}" ${variable.required ? 'required' : ''}>
                        <small class="variable-helper">Parameter: ${variable.name}</small>
                    </div>
                `;
                        });
                    }

                    // LIMITED TIME OFFER VARIABLES
                    if (variables.limited_time_offer.length > 0) {
                        variablesHtml += `<h6 class="mt-3">Limited Time Offer</h6>`;
                        variables.limited_time_offer.forEach(variable => {
                            variablesHtml += `
                    <div class="variable-input-group">
                        <label class="variable-label">${variable.displayName}</label>
                        <input type="${variable.type}" class="form-control" 
                            name="limited_time_offer_${variable.name}" 
                            placeholder="${variable.placeholder}" 
                            ${variable.required ? 'required' : ''}>
                        <small class="variable-helper">Time in milliseconds (e.g., 86400000 for 24 hours)</small>
                    </div>
                `;
                        });
                    }

                    // BUTTON VARIABLES
                    if (variables.buttons.length > 0) {
                        variablesHtml += `<h6 class="mt-3">Button Variables</h6>`;
                        variables.buttons.forEach(variable => {
                            variablesHtml += `
                    <div class="variable-input-group">
                        <label class="variable-label">${variable.displayName}</label>
                        <input type="${variable.type}" class="form-control" 
                            name="button_${variable.buttonIndex}" 
                            placeholder="${variable.placeholder}" 
                            ${variable.required ? 'required' : ''}>
                        <small class="variable-helper">Type: ${variable.buttonType}</small>
                    </div>
                `;
                        });
                    }


                    if (!variablesHtml) {
                        variablesHtml = '<p class="text-muted">No variables needed for this template</p>';
                    }

                    $('#templateVariablesContainer').html(variablesHtml);

                    $('#templatePreviewModal').modal('show');
                }

                function getAcceptAttribute(format) {
                    switch (format) {
                        case 'IMAGE':
                            return 'image/*';
                        case 'VIDEO':
                            return 'video/*';
                        case 'DOCUMENT':
                            return '.pdf,.doc,.docx,.txt';
                        default:
                            return '*';
                    }
                }

                function safeForEach(array, callback) {
                    if (!array || !Array.isArray(array)) return;
                    array.forEach(callback);
                }

                function setupMediaPreview(format) {
                    const $mediaPreview = $('#mediaPreview');
                    $mediaPreview.empty();

                    let placeholderHtml = '';
                    const acceptTypes = getAcceptAttribute(format);

                    switch (format) {
                        case 'IMAGE':
                            placeholderHtml = `
                    <div class="text-center">
                        <ion-icon name="image-outline" style="font-size: 3rem; color: #6c757d;"></ion-icon>
                        <p class="mt-2">Upload image for header</p>
                    </div>
                `;
                            break;
                        case 'VIDEO':
                            placeholderHtml = `
                    <div class="text-center">
                        <ion-icon name="videocam-outline" style="font-size: 3rem; color: #6c757d;"></ion-icon>
                        <p class="mt-2">Upload video for header</p>
                    </div>
                `;
                            break;
                        case 'DOCUMENT':
                            placeholderHtml = `
                    <div class="text-center">
                        <ion-icon name="document-outline" style="font-size: 3rem; color: #6c757d;"></ion-icon>
                        <p class="mt-2">Upload document for header</p>
                    </div>
                `;
                            break;
                    }

                    $mediaPreview.html(placeholderHtml);

                    $('#headerMedia').off('change').on('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            previewMediaFile(file, format);
                        }
                    });
                }

                function previewMediaFile(file, format) {
                    const $mediaPreview = $('#mediaPreview');
                    const reader = new FileReader();

                    if (format === 'IMAGE' && file.type.startsWith('image/')) {
                        reader.onload = function(e) {
                            $mediaPreview.html(`<img src="${e.target.result}" class="img-fluid">`);
                        };
                        reader.readAsDataURL(file);
                    } else if (format === 'VIDEO' && file.type.startsWith('video/')) {
                        reader.onload = function(e) {
                            $mediaPreview.html(`
                    <video controls class="img-fluid">
                        <source src="${e.target.result}" type="${file.type}">
                        Your browser does not support the video tag.
                    </video>
                `);
                        };
                        reader.readAsDataURL(file);
                    } else if (format === 'DOCUMENT') {
                        $mediaPreview.html(`
                <div class="document-preview">
                    <ion-icon name="document-outline" style="font-size: 3rem;"></ion-icon>
                    <p class="mt-2 mb-0">${file.name}</p>
                    <small>${(file.size / 1024).toFixed(2)} KB</small>
                </div>
            `);
                    }
                }

                $('#sendTemplateBtn').on('click', function() {
                    const headerFormat = getHeaderFormat(selectedTemplate);
                    const mediaFile = $('#headerMedia')[0].files[0];

                    if (!currentActiveChat) {
                        toastr.error('Please select a conversation first');
                        return;
                    }
                    if (!selectedTemplate) {
                        toastr.error('No template selected');
                        return;
                    }

                    const accountId = $('#accountFilter').val();
                    if (!accountId) {
                        toastr.error('Please select an account first');
                        return;
                    }

                    const formData = new FormData();
                    if (headerFormat && headerFormat !== 'TEXT' && mediaFile) {
                        formData.append('header_media', mediaFile);
                        formData.append('header_format', headerFormat);
                    }

                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('account_id', accountId);
                    formData.append('conversation_id', currentActiveChat);
                    formData.append('template_name', selectedTemplate.name);
                    formData.append('template_language', selectedTemplate.language);

                    const variables = extractVariablesFromTemplate(selectedTemplate);
                    let hasErrors = false;

                    // HEADER VARIABLES
                    const headerVars = [];
                    if (variables.header && variables.header.length > 0) {
                        safeForEach(variables.header, (variable) => {
                            const inputElement = $(`input[name="header_var_${variable.name}"]`);
                            if (inputElement.length) {
                                const inputValue = inputElement.val().trim();

                                if (!inputValue && inputElement.prop('required')) {
                                    toastr.error(`Please fill in ${variable.displayName}`);
                                    inputElement.focus();
                                    hasErrors = true;
                                    return false;
                                }

                                if (inputValue) {
                                    headerVars.push({
                                        'name': variable.name,
                                        'value': inputValue
                                    });
                                }
                            }
                        });

                        if (headerVars.length > 0) {
                            formData.append('header_variables', JSON.stringify(headerVars));
                        }
                    }

                    if (hasErrors) return;

                    // BODY VARIABLES
                    const bodyVars = [];
                    if (variables.body && variables.body.length > 0) {
                        safeForEach(variables.body, (variable) => {
                            const inputElement = $(`input[name="body_var_${variable.name}"]`);
                            if (inputElement.length) {
                                const inputValue = inputElement.val().trim();

                                if (!inputValue && inputElement.prop('required')) {
                                    toastr.error(`Please fill in ${variable.displayName}`);
                                    inputElement.focus();
                                    hasErrors = true;
                                    return false;
                                }

                                if (inputValue) {
                                    bodyVars.push({
                                        'name': variable.name,
                                        'value': inputValue
                                    });
                                }
                            }
                        });

                        if (bodyVars.length > 0) {
                            formData.append('body_variables', JSON.stringify(bodyVars));
                        }
                    }

                    if (hasErrors) return;

                    // LIMITED TIME OFFER VARIABLES
                    const limitedTimeOfferVars = [];
                    if (variables.limited_time_offer && variables.limited_time_offer.length > 0) {
                        safeForEach(variables.limited_time_offer, (variable) => {
                            const inputElement = $(`input[name="limited_time_offer_${variable.name}"]`);
                            if (inputElement.length) {
                                const inputValue = inputElement.val().trim();

                                if (!inputValue && inputElement.prop('required')) {
                                    toastr.error(`Please fill in ${variable.displayName}`);
                                    inputElement.focus();
                                    hasErrors = true;
                                    return false;
                                }

                                if (inputValue) {
                                    limitedTimeOfferVars.push({
                                        'name': variable.name,
                                        'value': inputValue
                                    });
                                }
                            }
                        });

                        if (limitedTimeOfferVars.length > 0) {
                            formData.append('limited_time_offer_variables', JSON.stringify(
                                limitedTimeOfferVars));
                        }
                    }

                    if (hasErrors) return;

                    // BUTTON VARIABLES
                    const buttonVars = [];
                    if (variables.buttons && variables.buttons.length > 0) {
                        safeForEach(variables.buttons, (variable) => {
                            const inputElement = $(`input[name="button_${variable.buttonIndex}"]`);
                            if (inputElement.length) {
                                const inputValue = inputElement.val().trim();

                                if (!inputValue && inputElement.prop('required')) {
                                    toastr.error(`Please fill in ${variable.displayName}`);
                                    inputElement.focus();
                                    hasErrors = true;
                                    return false;
                                }

                                if (inputValue) {
                                    buttonVars.push({
                                        'buttonIndex': variable.buttonIndex,
                                        'buttonType': variable.buttonType,
                                        'value': inputValue
                                    });
                                }
                            }
                        });

                        if (buttonVars.length > 0) {
                            formData.append('button_variables', JSON.stringify(buttonVars));
                        }
                    }

                    if (hasErrors) return;

                    const sendBtn = $('#sendTemplateBtn');
                    const originalText = sendBtn.html();
                    sendBtn.prop('disabled', true).html(`
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        Sending...
                    `);

                    $.ajax({
                        url: '{{ route('whatsapp-template.send-template') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Template message sent successfully');
                                $('#templatePreviewModal').modal('hide');

                                if (response.message) {
                                    console.log('Sent message:', response.message);
                                    appendMessage(response.message);
                                    updateConversationList(response.message, response.message
                                        .contact_number, response.message.contact_name);
                                }
                            } else {
                                let errorMsg = response.message ||
                                    'Failed to send template message';

                                if (response.error_code) {
                                    switch (response.error_code) {
                                        case 131047:
                                            errorMsg =
                                                'Cannot send template: 24-hour window expired. Customer needs to message first.';
                                            break;
                                        case 132000:
                                            errorMsg = 'Template not found or not approved.';
                                            break;
                                        case 131026:
                                            errorMsg =
                                                'Payment required for WhatsApp Business API.';
                                            break;
                                    }
                                }

                                toastr.error(errorMsg);
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Failed to send template message';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                errorMsg = response.message || errorMsg;
                            } catch (e) {
                                errorMsg = xhr.statusText || errorMsg;
                            }
                            toastr.error(errorMsg);
                        },
                        complete: function() {
                            sendBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });


                function loadContactDetails(phoneNumber) {
                    if (!phoneNumber) {
                        $('#contactSidebar').hide();
                        return;
                    }

                    showLoading();
                    console.log(phoneNumber)
                    $.ajax({
                        url: '/whatsapp-template/get-contact-details/' + phoneNumber,
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.client) {
                                populateContactDisplay(response.client, response.labels || [], response
                                    .cities || []);
                                $('#contactSidebar').show();
                            } else {
                                const newClient = {
                                    name: $('#activeChatName').text().split(' (+')[0],
                                    phone1: phoneNumber,
                                    phone2: '',
                                    address: '',
                                    id_city: '',
                                    seller_note: ''
                                };
                                populateContactDisplay(newClient, [], response.cities || []);
                                $('#contactSidebar').show();
                            }
                        },
                        error: function() {
                            toastr.error('Failed to load contact details');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function populateContactDisplay(client, labels, cities = []) {
                    $('#displayClientName').text(client.name || '-');
                    $('#ClientName').text(client.name || '-');
                    $('#displayClientPhone1').text(client.phone1 || '-');
                    $('#displayClientPhone2').text(client.phone2 || '-');
                    $('#displayClientAddress').text(client.address || '-');
                    $('#displayClientNotes').text(client.seller_note || '-');

                    let cityName = '-';
                    if (client.id_city && cities.length > 0) {
                        const city = cities.find(c => c.id == client.id_city);
                        if (city) {
                            cityName = `${city.name}${city.last_mille ? ' / ' + city.last_mille : ''}`;
                        }
                    }
                    $('#displayClientCity').text(cityName);

                    $('#editClientId').val(client.id || '');
                    $('#editClientName').val(client.name || '');
                    $('#editClientPhone1').val(client.phone1 || '');
                    $('#editClientPhone2').val(client.phone2 || '');
                    $('#editClientAddress').val(client.address || '');
                    $('#editClientNotes').val(client.seller_note || '');

                    if (cities.length > 0) {
                        let options = '<option value="">Select City</option>';
                        cities.forEach(city => {
                            options +=
                                `<option value="${city.id}" ${city.id == client.id_city ? 'selected' : ''}>${city.name}${city.last_mille ? ' / ' + city.last_mille : ''}</option>`;
                        });
                        $('#editClientCity').html(options);
                    }

                    renderContactTags(labels);
                    if (client.id) {
                        loadAssignedAgent(client.id);
                        loadOrderHistory(client.id);
                    } else {
                        $('#assignedAgentInfo').html('<div class="text-muted">No agent assigned</div>');
                        $('#orderHistoryList').html('<div class="text-muted">No order history</div>');
                    }
                }



                $(document).on('click', '#editContactModalBtn', function() {
                    $('#editContactDetailsModal').modal('show');
                });

                $(document).on('click', '#saveContactDetailsBtn', function() {
                    const clientId = $('#editClientId').val();
                    const formData = $('#editContactDetailsForm').serialize();

                    $.ajax({
                        url: '/whatsapp-template/update-contact-details/' + (clientId || 'new'),
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Contact details updated successfully');
                                $('#editContactDetailsModal').modal('hide');

                                if (response.client) {
                                    const phoneNumber = $('.chatlist .block.active').data(
                                        'contact');
                                    if (phoneNumber) {
                                        loadContactDetails(phoneNumber);
                                    }
                                }
                            } else {
                                toastr.error(response.message ||
                                    'Failed to update contact details');
                            }
                        },
                        error: function() {
                            toastr.error('Failed to update contact details');
                        }
                    });
                });

                $(document).on('click', '#createLeadSidebarBtn', function() {
                    if (!currentActiveChat) {
                        toastr.error('Please select a conversation first');
                        return;
                    }

                    const contactName = $('#activeChatName').text();
                    const contactNumber = $('.chatlist .block.active').data('contact');

                    $('#lead_name').val(contactName);
                    $('#lead_mobile').val(contactNumber);
                    $('#whatsapp_contact_name').val(contactName);
                    $('#whatsapp_contact_number').val(contactNumber);

                    $('#whatsappLeadModal').modal('show');
                });

                $(document).on('click', '#assignAgentBtn', function() {
                    loadAgentsWithWorkload();
                    $('#assignAgentModal').modal('show');

                    // Reset the assignment form
                    $('#agentSelection').show();
                    $('#assignmentReason').hide();
                    $('#assignmentReasonText').val('');
                    $('#assignmentPriority').val('medium');
                });
                $(document).on('input', '#agentSearch', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    $('.agent-item').each(function() {
                        const agentName = $(this).find('.agent-name').text().toLowerCase();
                        if (agentName.includes(searchTerm)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });

                $(document).on('click', '#viewProfileBtn', function() {
                    const clientId = $('#editClientId').val();
                    if (clientId) {
                        window.open('/clients/' + clientId + '/details', '_blank');
                    } else {
                        toastr.info('This contact does not have a full profile yet');
                    }
                });

                $(document).on('click', '#callContactBtn', function() {
                    const phoneNumber = $('#displayClientPhone1').text();
                    if (phoneNumber && phoneNumber !== '-') {
                        toastr.info('Call functionality would be implemented here. Number: ' + phoneNumber);
                    } else {
                        toastr.error('No phone number available for this contact');
                    }
                });

                function loadAssignedAgent(clientId) {
                    if (!clientId) {
                        $('#assignedAgentInfo').html('<div class="text-muted">No agent assigned</div>');
                        return;
                    }

                    $.ajax({
                        url: '/whatsapp/get-assigned-agent/' + clientId,
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.agent) {
                                const agent = response.agent;
                                $('#assignedAgentInfo').html(`
                        <div class="d-flex align-items-center">
                            <div class="agent-avatar me-2">
                                <img src="${agent.avatar || '/public/assets/images/default-avatar.png'}" alt="${agent.name}">
                            </div>
                            <div>
                                <div class="agent-name">${agent.name}</div>
                                <div class="agent-role">${agent.role || 'Agent'}</div>
                            </div>
                        </div>
                    `);
                            } else {
                                $('#assignedAgentInfo').html(
                                    '<div class="text-muted">No agent assigned</div>');
                            }
                        },
                        error: function() {
                            $('#assignedAgentInfo').html(
                                '<div class="text-muted">Error loading agent</div>');
                        }
                    });
                }

                function loadOrderHistory(clientId) {
                    if (!clientId) {
                        $('#orderHistoryList').html('<div class="text-muted">No order history</div>');
                        return;
                    }

                    $.ajax({
                        url: '/whatsapp/client-orders/' + clientId,
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.orders && response.orders.length > 0) {
                                let ordersHtml = '';
                                response.orders.forEach(order => {
                                    const statusClass = getStatusClass(order.status);
                                    ordersHtml += `
                        <div class="order-item">
                            <div class="order-header">
                                <div class="order-date">${order.date}</div>
                                <div class="order-status ${statusClass}">${order.status}</div>
                            </div>
                            <div class="order-details">
                                <div class="order-products">${order.products}</div>
                                <div class="order-amount">${order.amount} DH</div>
                            </div>
                            <div class="order-actions">
                                <a href="${order.edit_url}" target="_blank" class="btn btn-sm btn-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    `;
                                });
                                $('#orderHistoryList').html(ordersHtml);
                            } else {
                                $('#orderHistoryList').html(
                                    '<div class="text-muted">No order history</div>');
                            }
                        },
                        error: function() {
                            $('#orderHistoryList').html(
                                '<div class="text-muted">Error loading order history</div>');
                        }
                    });
                }

                function getStatusClass(status) {
                    switch (status?.toLowerCase()) {
                        case 'completed':
                            return 'completed';
                        case 'pending':
                            return 'pending';
                        case 'cancelled':
                            return 'cancelled';
                        default:
                            return '';
                    }
                }

                function formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }

                function loadAgentsWithWorkload() {
                    $.ajax({
                        url: '/whatsapp/agents-with-workload',
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                renderAgentsList(response.agents);
                            } else {
                                $('#agentsList').html(
                                    '<div class="text-center py-3 text-muted">No agents found</div>');
                            }
                        },
                        error: function() {
                            $('#agentsList').html(
                                '<div class="text-center py-3 text-muted">Error loading agents</div>');
                        }
                    });
                }

                function renderAgentsList(agents) {
                    let agentsHtml = '';

                    const managers = agents.filter(agent => agent.role == 4);
                    const regularAgents = agents.filter(agent => agent.role == 3);

                    if (managers.length > 0) {
                        agentsHtml += `<div class="agent-group">
                            <div class="agent-group-header">Managers</div>`;

                        managers.forEach(agent => {
                            agentsHtml += createAgentCard(agent);
                        });
                        agentsHtml += `</div>`;
                    }

                    if (regularAgents.length > 0) {
                        agentsHtml += `<div class="agent-group">
                            <div class="agent-group-header">Agents</div>`;

                        regularAgents.forEach(agent => {
                            agentsHtml += createAgentCard(agent);
                        });
                        agentsHtml += `</div>`;
                    }
                    console.log(agentsHtml);

                    $('#agentsList').html(agentsHtml);

                    $('.agent-item').on('click', function() {
                        const agentId = $(this).data('agent-id');
                        const agentName = $(this).data('agent-name');
                        const agentRole = $(this).data('agent-role');

                        $('#selectedAgentId').val(agentId);
                        $('#selectedAgentName').text(agentName);
                        $('#selectedAgentRole').text(agentRole);

                        $('#agentSelection').hide();
                        $('#assignmentReason').show();
                        $("#confirmAssignmentBtn").show();
                    });
                }

                function createAgentCard(agent) {
                    const workloadClass = getWorkloadClass(agent.workload_score);

                    return `
                        <div class="agent-item" data-agent-id="${agent.id}" data-agent-name="${agent.name}" data-agent-role="${agent.role_name}">
                            <div class="agent-avatar">
                                <img src="${agent.avatar}" alt="${agent.name}">
                            </div>
                            <div class="agent-info">
                                <div class="agent-name">${agent.name}</div>
                                <div class="agent-role">${agent.role_name}</div>
                                <div class="agent-workload">
                                    <span class="workload-badge ${workloadClass}">
                                        ${agent.unread_count} unread • ${agent.active_assignments} active
                                    </span>
                                </div>
                            </div>
                            <div class="agent-stats">
                                <div class="stat">
                                    <span class="stat-label">Unread</span>
                                    <span class="stat-value">${agent.unread_count}</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">Active</span>
                                    <span class="stat-value">${agent.active_assignments}</span>
                                </div>
                            </div>
                        </div>
                    `;
                }

                function getWorkloadClass(score) {
                    if (score === 0) return 'workload-low';
                    if (score <= 3) return 'workload-medium';
                    if (score <= 6) return 'workload-high';
                    return 'workload-urgent';
                }

                $(document).on('click', '#confirmAssignmentBtn', function() {
                    assignConversationToAgent();
                });

                function assignConversationToAgent() {
                    const conversationId = currentActiveChat;
                    const agentId = $('#selectedAgentId').val();
                    const reason = $('#assignmentReasonText').val().trim();
                    const priority = $('#assignmentPriority').val();

                    if (!agentId) {
                        toastr.error('Please select an agent');
                        return;
                    }

                    const btn = $('#confirmAssignmentBtn');
                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm"></span> Assigning...');

                    $.ajax({
                        url: '/whatsapp/assign-conversation',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            conversation_id: conversationId,
                            assigned_to: agentId,
                            reason: reason,
                            priority: priority
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Conversation assigned successfully');
                                $('#assignAgentModal').modal('hide');
                                loadCurrentAssignment();
                                updateAssignmentUI(response.assignment);
                            } else {
                                toastr.error(response.message || 'Failed to assign conversation');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to assign conversation');
                        },
                        complete: function() {
                            btn.prop('disabled', false).html('Confirm Assignment');
                        }
                    });
                }

                function loadCurrentAssignment() {
                    if (!currentActiveChat) return;

                    $.ajax({
                        url: '/whatsapp/current-assignment/' + currentActiveChat,
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                updateAssignmentUI(response.assignment);
                            }
                        }
                    });
                }

                function updateAssignmentUI(assignment) {
                    const $assignmentInfo = $('#assignedAgentInfo');

                    if (assignment && assignment.assigned_to) {
                        $assignmentInfo.html(`
                            <div class="assignment-info">
                                <div class="assigned-agent">
                                    <div class="agent-avatar-small">
                                        <img src="${assignment.assigned_to.avatar || '/public/assets/images/default-avatar.png'}" alt="${assignment.assigned_to.name}">
                                    </div>
                                    <div class="agent-details">
                                        <div class="agent-name">${assignment.assigned_to.name}</div>
                                        <div class="agent-role">${assignment.assigned_to.role === 4 ? 'Manager' : 'Agent'}</div>
                                        <div class="assignment-priority priority-${assignment.priority}">
                                            ${assignment.priority} priority
                                        </div>
                                    </div>
                                </div>
                                ${assignment.reason ? `<div class="assignment-reason"><strong>Reason:</strong> ${assignment.reason}</div>` : ''}
                                <div class="assignment-meta">
                                    <small>Assigned by ${assignment.assigned_by.name} on ${new Date(assignment.assigned_at).toLocaleDateString()}</small>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary mt-2" id="viewAssignmentHistoryBtn">
                                    View History
                                </button>
                            </div>
                        `);
                    } else {
                        $assignmentInfo.html('<div class="text-muted">No agent assigned</div>');
                    }
                }

                $(document).on('click', '#viewAssignmentHistoryBtn', function() {
                    viewAssignmentHistory();
                });

                function viewAssignmentHistory() {
                    if (!currentActiveChat) return;

                    $.ajax({
                        url: '/whatsapp/assignment-history/' + currentActiveChat,
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                showAssignmentHistory(response.assignments);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error loading assignment history:', xhr);
                        }
                    });
                }

                function showAssignmentHistory(assignments) {
                    let historyHtml = '';

                    if (assignments.length === 0) {
                        historyHtml = '<div class="text-center py-3 text-muted">No assignment history</div>';
                    } else {
                        assignments.forEach(assignment => {
                            historyHtml += `
                                <div class="assignment-history-item">
                                    <div class="assignment-header">
                                        <strong>${assignment.assigned_to.name}</strong>
                                        <span class="priority-badge priority-${assignment.priority}">${assignment.priority}</span>
                                    </div>
                                    <div class="assignment-details">
                                        <small>Assigned by ${assignment.assigned_by.name} on ${new Date(assignment.assigned_at).toLocaleDateString()}</small>
                                        ${assignment.reason ? `<div class="assignment-reason">${assignment.reason}</div>` : ''}
                                        ${assignment.resolved_at ? `<div class="resolved-date">Resolved: ${new Date(assignment.resolved_at).toLocaleDateString()}</div>` : ''}
                                    </div>
                                </div>
                            `;
                        });
                    }

                    if (!$('#assignmentHistoryModal').length) {
                        $('body').append(`
                            <div class="modal fade" id="assignmentHistoryModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Assignment History</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="assignmentHistoryContent">${historyHtml}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    } else {
                        $('#assignmentHistoryContent').html(historyHtml);
                    }

                    $('#assignmentHistoryModal').modal('show');
                }



                function loadContactDetails(phoneNumber) {
                    if (!phoneNumber) {
                        $('#contactSidebar').hide();
                        return;
                    }

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/get-contact-details/' + phoneNumber,
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.client) {
                                populateContactDisplay(response.client, response.labels || [], response
                                    .cities || []);
                                $('#contactSidebar').show();

                                if (response.client.id) {
                                    loadOrderHistory(response.client.id);
                                    loadCurrentAssignment();
                                }
                            } else {
                                const newClient = {
                                    name: $('#activeChatName').text().split(' (+')[0],
                                    phone1: phoneNumber,
                                    phone2: '',
                                    address: '',
                                    id_city: '',
                                    seller_note: ''
                                };
                                populateContactDisplay(newClient, [], response.cities || []);
                                $('#contactSidebar').show();
                            }
                        },
                        error: function() {
                            toastr.error('Failed to load contact details');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function renderContactTags(labels) {
                    const $tagsContainer = $('#contactTags');
                    $tagsContainer.empty();

                    if (labels.length === 0) {
                        $tagsContainer.html('<div class="text-muted">No tags added yet</div>');
                    } else {
                        labels.forEach(label => {
                            const tagHtml = `
                    <div style="background:${label.color}" class="tag" data-tag-id="${label.id}">
                        ${label.name}
                    </div>
                `;
                            $tagsContainer.append(tagHtml);
                        });
                    }
                }

                $(document).on('click', '#editContactBtn', function(e) {
                    e.preventDefault();
                    $('#clientName, #clientPhone1, #clientPhone2, #clientAddress, #clientCity, #clientNotes')
                        .prop('readonly', false)
                        .prop('disabled', false);
                    $('#contactFormActions').show();
                    $(this).hide();
                });

                $(document).on('click', '#cancelEditBtn', function(e) {
                    e.preventDefault();
                    $('#clientName, #clientPhone1, #clientPhone2, #clientAddress, #clientCity, #clientNotes')
                        .prop('readonly', true)
                        .prop('disabled', true);
                    $('#contactFormActions').hide();
                    $('#editContactBtn').show();

                    const phoneNumber = $('.chatlist .block.active').data('contact');
                    if (phoneNumber) {
                        loadContactDetails(phoneNumber);
                    }
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $("#saveContactFormBtn").click(function(e) {
                    e.preventDefault();
                    const clientId = $('#clientId').val();
                    const formData = $('#contactDetailsForm').serialize();

                    $.ajax({
                        url: '/whatsapp-template/update-contact-details/' + (clientId || 'new'),
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Contact details updated successfully');
                                $('#clientName, #clientPhone1, #clientPhone2, #clientAddress, #clientCity, #clientNotes')
                                    .prop('readonly', true)
                                    .prop('disabled', true);
                                $('#contactFormActions').hide();

                                if (response.client && response.client.id) {
                                    $('#clientId').val(response.client.id);
                                }
                            } else {
                                toastr.error(response.message ||
                                    'Failed to update contact details');
                            }
                        },
                        error: function() {
                            toastr.error('Failed to update contact details');
                        }
                    });
                });

                function initEnhancedSearchFunctionality() {
                    $(document).on('click', '.nav_icons .li[name="search-outline"]', function() {
                        openSearchModal();
                    });

                    let searchTimeout;
                    $('#messageSearchInput').on('input', function() {
                        clearTimeout(searchTimeout);
                        const term = $(this).val().trim();

                        if (term.length === 0) {
                            clearSearch();
                            return;
                        }

                        if (term.length < 2) {
                            $('#searchResultsCount').text('Enter at least 2 characters');
                            $('#searchResults').html('');
                            return;
                        }

                        searchTimeout = setTimeout(() => {
                            performSearch(term);
                        }, 300);
                    });

                    $('#messageSearchInput').on('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (e.shiftKey) {
                                navigateToPreviousResult();
                            } else {
                                navigateToNextResult();
                            }
                        } else if (e.key === 'Escape') {
                            $('#searchMessagesModal').modal('hide');
                        }
                    });


                    $('#advancedSearchBtn').on('click', toggleAdvancedSearch);
                    $('#applyAdvancedSearch').on('click', applyAdvancedSearch);

                    $('#searchDateFrom, #searchDateTo, #searchMessageType, #searchSender').on('change', function() {
                        if ($('#messageSearchInput').val().trim().length >= 2) {
                            performSearch($('#messageSearchInput').val().trim());
                        }
                    });

                    $('#matchCase').on('change', function() {
                        searchFilters.matchCase = $(this).is(':checked');
                        if ($('#messageSearchInput').val().trim().length >= 2) {
                            performSearch($('#messageSearchInput').val().trim());
                        }
                    });

                    $('#searchInOlderMessages').on('click', searchInOlderMessages);

                    $('#searchMessagesModal').on('shown.bs.modal', function() {
                        $('#messageSearchInput').focus();
                        clearSearch();
                        loadSearchHistory();
                    });

                    $('#searchMessagesModal').on('hidden.bs.modal', function() {
                        clearHighlights();
                        clearSearch();
                        stopVoiceSearch();
                        $('#advancedSearchOptions').slideUp();
                    });

                    initKeyboardShortcuts();
                }

                function openSearchModal() {
                    $('#searchMessagesModal').modal('show');
                    setTimeout(() => {
                        $('#messageSearchInput').focus();

                        const recentSearches = getRecentSearches();
                        if (recentSearches.length > 0 && $('#messageSearchInput').val() === '') {
                            showRecentSearches(recentSearches);
                        }
                    }, 300);
                }

                function initKeyboardShortcuts() {
                    $(document).on('keydown', function(e) {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                            e.preventDefault();
                            if (currentActiveChat) {
                                openSearchModal();
                            } else {
                                toastr.info('Please select a conversation first');
                            }
                        }

                        if ($('#searchMessagesModal').hasClass('show')) {
                            if (e.key === 'ArrowDown' && !e.ctrlKey && !e.metaKey) {
                                e.preventDefault();
                                navigateToNextResult();
                            } else if (e.key === 'ArrowUp' && !e.ctrlKey && !e.metaKey) {
                                e.preventDefault();
                                navigateToPreviousResult();
                            }
                        }
                    });
                }

                function toggleAdvancedSearch() {
                    const $options = $('#advancedSearchOptions');
                    if ($options.is(':visible')) {
                        $options.slideUp();
                        $('#advancedSearchBtn').removeClass('active');
                    } else {
                        $options.slideDown();
                        $('#advancedSearchBtn').addClass('active');
                        updateAdvancedSearchUI();
                    }
                }

                function updateAdvancedSearchUI() {
                    $('#searchDateFrom').val(searchFilters.dateFrom);
                    $('#searchDateTo').val(searchFilters.dateTo);
                    $('#searchMessageType').val(searchFilters.messageType);
                    $('#searchSender').val(searchFilters.sender);
                    $('#matchCase').prop('checked', searchFilters.matchCase);
                }

                function applyAdvancedSearch() {
                    searchFilters = {
                        dateFrom: $('#searchDateFrom').val(),
                        dateTo: $('#searchDateTo').val(),
                        messageType: $('#searchMessageType').val(),
                        sender: $('#searchSender').val(),
                        matchCase: $('#matchCase').is(':checked')
                    };

                    if ($('#messageSearchInput').val().trim().length >= 2) {
                        performSearch($('#messageSearchInput').val().trim());
                    }

                    updateFiltersInfo();
                }

                function updateFiltersInfo() {
                    let filters = [];

                    if (searchFilters.dateFrom || searchFilters.dateTo) {
                        filters.push('Date range');
                    }
                    if (searchFilters.messageType !== 'all') {
                        filters.push(searchFilters.messageType);
                    }
                    if (searchFilters.sender !== 'all') {
                        filters.push(searchFilters.sender);
                    }
                    if (searchFilters.matchCase) {
                        filters.push('Match Case');
                    }

                    if (filters.length > 0) {
                        $('#searchFiltersInfo').text('(' + filters.join(', ') + ')').show();
                    } else {
                        $('#searchFiltersInfo').hide();
                    }
                }

                function performSearch(term) {
                    if (!currentActiveChat) {
                        $('#searchResultsCount').text('No active conversation');
                        $('#searchResults').html('<div class="no-results">Please select a conversation first</div>');
                        return;
                    }

                    searchTerm = searchFilters.matchCase ? term : term.toLowerCase();
                    searchResults = [];
                    currentSearchIndex = -1;

                    $('#searchResults').html('<div class="search-loading">Searching messages...</div>');
                    $('#searchResultsCount').text('Searching...');
                    $('#currentResultPosition').hide();
                    $('#searchInOlderMessages').hide();

                    const messages = $('#chatMessages .message');
                    const results = [];

                    messages.each(function() {
                        const $message = $(this);
                        if (passesFilters($message)) {
                            const messageText = getMessageText($message);
                            const searchText = searchFilters.matchCase ? messageText : messageText
                                .toLowerCase();

                            if (searchText.includes(searchTerm)) {
                                const positions = findAllOccurrences(messageText, searchTerm, searchFilters
                                    .matchCase);

                                positions.forEach(position => {
                                    results.push({
                                        messageElement: $message,
                                        messageId: $message.data('id'),
                                        text: messageText,
                                        timestamp: $message.find('.timestamp').first().text()
                                            .trim(),
                                        isOutgoing: $message.hasClass('my_msg'),
                                        messageType: getMessageType($message),
                                        position: position,
                                        date: new Date($message.data('date') || $message.find(
                                            '.timestamp').first().text())
                                    });
                                });
                            }
                        }
                    });

                    searchResults = results;
                    displaySearchResults();
                    saveToSearchHistory(term);
                }

                function passesFilters($message) {
                    if (searchFilters.messageType !== 'all') {
                        const messageType = getMessageType($message);
                        if (searchFilters.messageType !== messageType) {
                            return false;
                        }
                    }

                    if (searchFilters.sender !== 'all') {
                        const isOutgoing = $message.hasClass('my_msg');
                        if ((searchFilters.sender === 'me' && !isOutgoing) ||
                            (searchFilters.sender === 'them' && isOutgoing)) {
                            return false;
                        }
                    }

                    if (searchFilters.dateFrom || searchFilters.dateTo) {
                        const messageDate = new Date($message.data('date') || $message.find('.timestamp').first()
                            .text());
                        if (searchFilters.dateFrom && messageDate < new Date(searchFilters.dateFrom)) {
                            return false;
                        }
                        if (searchFilters.dateTo && messageDate > new Date(searchFilters.dateTo + 'T23:59:59')) {
                            return false;
                        }
                    }

                    return true;
                }

                function getMessageType($message) {
                    if ($message.find('.deleted-message').length) return 'deleted';
                    if ($message.find('.media-message').length) return 'media';
                    if ($message.find('.template-message-preview').length) return 'template';
                    return 'text';
                }

                function getMessageText($message) {
                    let text = $message.find('.message-content p').text().trim();

                    if (!text) {
                        text = $message.find('.media-message').text().trim();
                    }

                    if (!text) {
                        text = $message.find('.template-message-preview').text().trim();
                    }

                    if (!text) {
                        text = $message.find('.deleted-message').text().trim();
                    }

                    return text || '';
                }

                function findAllOccurrences(text, term, matchCase = false) {
                    const positions = [];
                    let index = -1;
                    const searchText = matchCase ? text : text.toLowerCase();
                    const searchTerm = matchCase ? term : term.toLowerCase();

                    while ((index = searchText.indexOf(searchTerm, index + 1)) !== -1) {
                        positions.push({
                            start: index,
                            end: index + term.length
                        });
                    }

                    return positions;
                }

                function displaySearchResults() {
                    const $resultsContainer = $('#searchResults');
                    const $resultsCount = $('#searchResultsCount');

                    if (searchResults.length === 0) {
                        $resultsContainer.html('<div class="no-results">No messages found matching your search</div>');
                        $resultsCount.text('0 results');
                        $('#prevResult').prop('disabled', true);
                        $('#nextResult').prop('disabled', true);
                        $('#currentResultPosition').hide();
                        $('#searchInOlderMessages').show();
                        return;
                    }

                    $resultsCount.text(`${searchResults.length} result${searchResults.length !== 1 ? 's' : ''}`);
                    $('#prevResult').prop('disabled', false);
                    $('#nextResult').prop('disabled', false);
                    $('#currentResultPosition').show();
                    $('#searchInOlderMessages').hide();

                    let resultsHtml = '';

                    searchResults.forEach((result, index) => {
                        const preview = createSearchPreview(result.text, searchTerm, result.position,
                            searchFilters.matchCase);
                        const time = result.timestamp;
                        const direction = result.isOutgoing ? 'You' : $('#activeChatName').text().split(' (+')[
                            0];
                        const typeBadge = getTypeBadge(result.messageType);

                        resultsHtml += `
                            <div class="search-result-item" data-index="${index}">
                                <div class="search-result-message">${preview}</div>
                                <div class="search-result-meta">
                                    <span>${direction} ${typeBadge}</span>
                                    <span>${time}</span>
                                </div>
                            </div>
                        `;
                    });

                    $resultsContainer.html(resultsHtml);

                    $('.search-result-item').on('click', function() {
                        const index = parseInt($(this).data('index'));
                        navigateToResult(index);
                    });

                    if (searchResults.length > 0) {
                        navigateToResult(0);
                    }
                }

                function getTypeBadge(type) {
                    const badges = {
                        media: '<span class="search-result-type media">Media</span>',
                        template: '<span class="search-result-type template">Template</span>',
                        deleted: '<span class="search-result-type deleted">Deleted</span>',
                        text: ''
                    };
                    return badges[type] || '';
                }

                function createSearchPreview(text, term, position, matchCase = false) {
                    const start = Math.max(0, position.start - 20);
                    const end = Math.min(text.length, position.end + 20);
                    let preview = text.substring(start, end);

                    const highlightedTerm = text.substring(position.start, position.end);
                    const regex = new RegExp(matchCase ? highlightedTerm : highlightedTerm, 'gi');

                    preview = preview.replace(regex, match => `<span class="search-highlight">${match}</span>`);

                    if (start > 0) preview = '...' + preview;
                    if (end < text.length) preview = preview + '...';

                    return preview;
                }

                function navigateToResult(index) {
                    if (index < 0 || index >= searchResults.length) return;

                    $('.search-result-item').removeClass('active');
                    $(`.search-result-item[data-index="${index}"]`).addClass('active');

                    currentSearchIndex = index;
                    const result = searchResults[index];

                    scrollToMessage(result.messageElement);

                    highlightMessage(result.messageElement);

                    updateNavigationUI();
                }

                function navigateToNextResult() {
                    if (searchResults.length === 0) return;

                    let nextIndex = currentSearchIndex + 1;
                    if (nextIndex >= searchResults.length) {
                        nextIndex = 0;
                    }

                    navigateToResult(nextIndex);
                }

                function navigateToPreviousResult() {
                    if (searchResults.length === 0) return;

                    let prevIndex = currentSearchIndex - 1;
                    if (prevIndex < 0) {
                        prevIndex = searchResults.length - 1;
                    }

                    navigateToResult(prevIndex);
                }

                function updateNavigationUI() {
                    $('#currentResultPosition').text(`${currentSearchIndex + 1} of ${searchResults.length}`);
                    $('#prevResult').prop('disabled', searchResults.length === 0);
                    $('#nextResult').prop('disabled', searchResults.length === 0);
                }

                function initVoiceSearch() {
                    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                        voiceRecognition = new SpeechRecognition();
                        voiceRecognition.continuous = false;
                        voiceRecognition.interimResults = false;
                        voiceRecognition.lang = 'en-US';

                        $('#voiceSearchBtn').on('click', function() {
                            if (isVoiceSearchActive) {
                                stopVoiceSearch();
                            } else {
                                startVoiceSearch();
                            }
                        });

                        $('#stopVoiceSearch').on('click', stopVoiceSearch);

                        voiceRecognition.onresult = function(event) {
                            const transcript = event.results[0][0].transcript;
                            $('#messageSearchInput').val(transcript).trigger('input');
                            stopVoiceSearch();
                        };

                        voiceRecognition.onerror = function(event) {
                            console.error('Voice recognition error:', event.error);
                            stopVoiceSearch();
                            toastr.error('Voice recognition failed: ' + event.error);
                        };

                        voiceRecognition.onend = function() {
                            stopVoiceSearch();
                        };
                    } else {
                        $('#voiceSearchBtn').hide();
                    }
                }

                function startVoiceSearch() {
                    if (!voiceRecognition) return;

                    try {
                        voiceRecognition.start();
                        isVoiceSearchActive = true;
                        $('#voiceSearchBtn').addClass('recording');
                        $('#voiceSearchIndicator').show();
                        $('#messageSearchInput').attr('placeholder', 'Listening... Speak now');
                    } catch (error) {
                        console.error('Error starting voice recognition:', error);
                        toastr.error('Voice recognition not available');
                    }
                }

                function stopVoiceSearch() {
                    if (!voiceRecognition) return;

                    try {
                        voiceRecognition.stop();
                    } catch (error) {
                        // Ignore stop errors
                    }

                    isVoiceSearchActive = false;
                    $('#voiceSearchBtn').removeClass('recording');
                    $('#voiceSearchIndicator').hide();
                    $('#messageSearchInput').attr('placeholder', 'Search messages... (Ctrl+F)');
                }

                function searchInOlderMessages() {
                    if (!currentActiveChat || !currentActiveAccount) return;

                    showLoading();

                    $.ajax({
                        url: '/whatsapp-template/search-messages',
                        method: 'GET',
                        data: {
                            account_id: currentActiveAccount,
                            conversation_id: currentActiveChat,
                            search_term: searchTerm,
                            filters: JSON.stringify(searchFilters)
                        },
                        success: function(response) {
                            if (response.success) {
                                displayServerSearchResults(response.results);
                            } else {
                                toastr.error(response.message || 'Search failed');
                            }
                        },
                        error: function() {
                            toastr.error('Search failed');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function displayServerSearchResults(serverResults) {
                    console.log('Server search results:', serverResults);
                    toastr.info('Older messages search would be implemented here');
                }

                function saveToSearchHistory(term) {
                    let history = JSON.parse(localStorage.getItem('whatsappSearchHistory') || '[]');
                    history = history.filter(item => item !== term);
                    history.unshift(term);
                    history = history.slice(0, 10);
                    localStorage.setItem('whatsappSearchHistory', JSON.stringify(history));
                }

                function getRecentSearches() {
                    return JSON.parse(localStorage.getItem('whatsappSearchHistory') || '[]');
                }

                function loadSearchHistory() {
                    const recentSearches = getRecentSearches();
                    if (recentSearches.length > 0) {
                        // Could show recent searches dropdown
                    }
                }

                function showRecentSearches(searches) {
                    // Implement recent searches dropdown UI
                }

                function scrollToMessage($message) {
                    const chatbox = $('#chatMessages')[0];
                    const messageOffset = $message.offset().top;
                    const chatboxOffset = $(chatbox).offset().top;
                    const scrollPosition = messageOffset - chatboxOffset - 100;

                    $(chatbox).animate({
                        scrollTop: scrollPosition
                    }, 500);
                }

                function highlightMessage($message) {
                    clearHighlights();
                    $message.addClass('message-highlight');

                    setTimeout(() => {
                        $message.removeClass('message-highlight');
                    }, 3000);
                }

                function clearHighlights() {
                    $('.message').removeClass('message-highlight');
                }

                function clearSearch() {
                    searchResults = [];
                    currentSearchIndex = -1;
                    searchTerm = '';
                    $('#searchResults').html('');
                    $('#searchResultsCount').text('Enter search term');
                    $('#currentResultPosition').hide();
                    $('#searchFiltersInfo').hide();
                    $('#prevResult').prop('disabled', true);
                    $('#nextResult').prop('disabled', true);
                    $('#searchInOlderMessages').hide();
                }

                initEnhancedSearchFunctionality();
                initVoiceSearch();

            });
        </script>
    @endsection
