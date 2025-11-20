<style>
    /* Widget Container */
    #chat-widget-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: 'Segoe UI', sans-serif;
    }

    /* Tombol Bulat (Toggle) */
    #chat-toggle-btn {
        background-color: #8B5E3C;
        color: white;
        border: none;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: transform 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #chat-toggle-btn:hover { transform: scale(1.1); background-color: #6F4B30; }

    /* Kotak Chat */
    #chat-box {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 450px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    #chat-box.hidden { display: none !important; }

    /* Header */
    .chat-header {
        background-color: #8B5E3C;
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chat-header h4 { margin: 0; font-size: 16px; font-weight: 600; }
    .close-btn { background: none; border: none; color: white; font-size: 24px; cursor: pointer; }

    /* Area Pesan */
    #chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background-color: #f3f4f6;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Bubble Chat */
    .message {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.4;
        word-wrap: break-word;
    }
    
    /* Pesan User (Kanan) */
    .user-message { 
        background-color: #8B5E3C; 
        color: white; 
        align-self: flex-end; 
        border-bottom-right-radius: 2px;
    }
    
    /* Pesan Bot (Kiri) */
    .bot-message { 
        background-color: white; 
        color: #333; 
        align-self: flex-start; 
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 2px;
    }

    /* Pesan Admin (Kiri - Spesial) */
    .admin-message {
        background-color: #e8f5e9; /* Hijau Muda */
        color: #333;
        align-self: flex-start;
        border: 1px solid #c3e6cb;
        border-left: 4px solid #28a745;
        border-bottom-left-radius: 2px;
    }

    /* Input Area */
    .chat-input-area {
        padding: 12px;
        border-top: 1px solid #e5e7eb;
        background: white;
        display: flex;
        gap: 8px;
        align-items: center;
    }
    #chat-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #d1d5db;
        border-radius: 25px;
        outline: none;
        font-size: 14px;
    }
    .send-btn {
        background-color: #8B5E3C;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .typing-indicator { font-size: 11px; color: #888; font-style: italic; margin-left: 10px; margin-bottom: 5px; }
</style>

<div id="chat-widget-container">
    <button id="chat-toggle-btn" onclick="toggleChat()">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    </button>

    <div id="chat-box" class="hidden">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="3"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <h4>CS E-Mebel</h4>
            </div>
            <button class="close-btn" onclick="toggleChat()">&times;</button>
        </div>

        <div id="chat-messages">
            </div>

        <div class="chat-input-area">
            <input type="text" id="chat-input" placeholder="Tulis pesan..." autocomplete="off" onkeypress="handleEnter(event)">
            <button class="send-btn" onclick="sendMessage()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div>
    </div>
</div>

<script>
    let pollingInterval = null; // Variabel untuk menyimpan Timer otomatis

    // --- A. BUKA / TUTUP CHAT ---
    function toggleChat() {
        const chatBox = document.getElementById('chat-box');
        
        if (chatBox.classList.contains('hidden')) {
            chatBox.classList.remove('hidden');
            
            // 1. Load data saat dibuka
            loadChatHistory();

            // 2. Mulai cek pesan baru setiap 3 detik (Realtime Sederhana)
            if (!pollingInterval) {
                pollingInterval = setInterval(loadChatHistory, 3000);
            }
            
            setTimeout(() => document.getElementById('chat-input').focus(), 100);
        } else {
            chatBox.classList.add('hidden');
            
            // Matikan pengecekan otomatis saat ditutup (biar hemat resource)
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }
    }

    // --- B. LOAD RIWAYAT CHAT (AUTO REFRESH) ---
    function loadChatHistory() {
        const container = document.getElementById('chat-messages');

        fetch("{{ route('chat.messages') }}")
            .then(response => response.json())
            .then(data => {
                // Cek apakah user sedang scroll ke atas? (biar gak keganggu autoscroll)
                const isScrolledToBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 50;

                // Simpan elemen loading jika sedang ada (biar gak hilang saat refresh)
                const loadingElem = document.getElementById('bot-typing');

                // Reset isi container
                let html = `
                    <div class="message bot-message">
                        Halo! 👋<br>Selamat datang di E-Mebel. Ada yang bisa saya bantu?
                    </div>
                `;

                data.forEach(chat => {
                    // LOGIKA: Membedakan Chat User, Bot, dan Admin
                    
                    if (chat.message === '(Pesan Admin)') {
                        // 1. INI PESAN MANUAL DARI ADMIN
                        html += `
                            <div class="message admin-message">
                                <b style="font-size:11px; color:#155724;">ADMIN SUPPORT:</b><br>
                                ${chat.reply.replace(/\n/g, '<br>')}
                            </div>
                        `;
                    } else {
                        // 2. INI PESAN USER BIASA
                        html += `<div class="message user-message">${chat.message}</div>`;
                        
                        // 3. INI BALASAN BOT (JIKA ADA)
                        if (chat.reply) {
                            html += `<div class="message bot-message">${chat.reply.replace(/\n/g, '<br>')}</div>`;
                        }
                    }
                });

                container.innerHTML = html;

                // Kembalikan loading indicator jika tadi ada
                if (loadingElem) container.appendChild(loadingElem);

                // Auto Scroll ke bawah hanya jika user memang ada di bawah
                if (isScrolledToBottom) {
                    scrollToBottom();
                }
            })
            .catch(err => console.error('Gagal load history:', err));
    }

    // --- C. KIRIM PESAN ---
    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }

    function sendMessage() {
        const inputField = document.getElementById('chat-input');
        const message = inputField.value.trim();
        
        if (message === "") return;

        // Tampilkan manual di layar biar instan
        const container = document.getElementById('chat-messages');
        container.insertAdjacentHTML('beforeend', `<div class="message user-message">${message}</div>`);
        inputField.value = '';
        scrollToBottom();

        // Tampilkan Loading
        container.insertAdjacentHTML('beforeend', `<div id="bot-typing" class="typing-indicator">Bot sedang mengetik...</div>`);
        scrollToBottom();

        // Kirim ke Laravel
        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Hapus loading
            const loadingElem = document.getElementById('bot-typing');
            if (loadingElem) loadingElem.remove();

            // Paksa refresh history agar balasan (Bot/Admin) sinkron
            loadChatHistory();
        })
        .catch(error => {
            console.error(error);
            const loadingElem = document.getElementById('bot-typing');
            if (loadingElem) loadingElem.remove();
            container.insertAdjacentHTML('beforeend', `<div class="message bot-message">Maaf, error koneksi.</div>`);
        });
    }

    function scrollToBottom() {
        const container = document.getElementById('chat-messages');
        container.scrollTop = container.scrollHeight;
    }
</script>