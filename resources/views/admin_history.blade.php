@if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th width="15%">Waktu</th>
                    <th width="15%">User</th>
                    <th width="30%">Isi Percakapan</th>
                    <th width="40%">Balas Pesan Manual</th> </tr>
            </thead>
            <tbody>
                @foreach($chats as $chat)
                <tr>
                    <td style="color: #666; font-size: 12px;">
                        {{ $chat->created_at->format('d M, H:i') }}
                    </td>

                    <td>
                        @if($chat->user)
                            <span class="badge badge-user">{{ $chat->user->name }}</span>
                        @else
                            <span class="badge badge-guest">Tamu</span>
                        @endif
                    </td>

                    <td>
                        <div class="user-text" style="font-size: 13px; font-weight:bold;">User: "{{ Str::limit($chat->message, 25) }}"</div>
                        <div class="bot-text" style="font-size: 13px; font-style:italic;">Jawab: "{{ Str::limit($chat->reply, 25) }}"</div>
                    </td>

                    <td>
                        @if($chat->user_id)
                            <form action="{{ route('admin.reply') }}" method="POST" style="display: flex; gap: 5px;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $chat->user_id }}">
                                
                                <input type="text" name="reply" placeholder="Ketik balasan..." required 
                                    style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                                
                                <button type="submit" style="background: #8B5E3C; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer;">
                                    ➤
                                </button>
                            </form>
                        @else
                            <span style="font-size: 11px; color: #aaa;">(Tamu tidak bisa dibalas)</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>