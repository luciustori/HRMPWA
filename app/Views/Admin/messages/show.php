<div class="max-w-4xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Baca Pesan</h1>
            <p class="text-gray-500 text-sm">Percakapan dengan <?= htmlspecialchars($message['sender_username'] == $_SESSION['username'] ? $message['recipient_username'] : $message['sender_username']) ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?= BASEURL ?>/admin/messages" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-bold shadow-sm transition">
                <i class="ri-arrow-left-line mr-1"></i> Kembali ke Inbox
            </a>
            <button onclick="document.getElementById('reply-area').scrollIntoView({behavior: 'smooth'})" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-bold shadow-md transition">
                <i class="ri-reply-line mr-1"></i> Balas
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                        <?= substr($message['sender_username'], 0, 1) ?>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($message['subject']) ?></h2>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <span class="font-medium text-gray-700"><?= htmlspecialchars($message['s_fname'] . ' ' . $message['s_lname']) ?></span>
                            <span>&bull;</span>
                            <span><?= date('d M Y H:i', strtotime($message['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
                <?php if(!empty($message['attachment'])): ?>
                    <a href="<?= BASEURL ?>/uploads/messages/<?= $message['attachment'] ?>" target="_blank" class="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold hover:bg-indigo-100 transition">
                        <i class="ri-attachment-2"></i> Lampiran
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="p-8 prose max-w-none text-gray-700 leading-relaxed">
            <?= nl2br(htmlspecialchars($message['body'])) ?>
        </div>
    </div>

    <?php if(!empty($replies)): ?>
        <div class="space-y-6 mb-10 relative before:absolute before:left-8 before:top-0 before:h-full before:w-0.5 before:bg-gray-200">
            <?php foreach($replies as $r): 
                $isMe = ($r['sender_id'] == $_SESSION['user_id']);
            ?>
                <div class="relative pl-16">
                    <div class="absolute left-6 top-0 w-4 h-4 rounded-full border-2 border-white <?= $isMe ? 'bg-indigo-500' : 'bg-gray-400' ?> z-10 box-content"></div>
                    
                    <div class="bg-white p-5 rounded-xl border <?= $isMe ? 'border-indigo-100 bg-indigo-50/30' : 'border-gray-200' ?> shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-bold text-sm <?= $isMe ? 'text-indigo-700' : 'text-gray-700' ?>">
                                <?= $isMe ? 'Saya' : htmlspecialchars($r['s_fname'] . ' ' . $r['s_lname']) ?>
                            </span>
                            <span class="text-xs text-gray-400"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></span>
                        </div>
                        <div class="text-gray-700 text-sm leading-relaxed">
                            <?= nl2br(htmlspecialchars($r['body'])) ?>
                        </div>
                        <?php if(!empty($r['attachment'])): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <a href="<?= BASEURL ?>/uploads/messages/<?= $r['attachment'] ?>" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:underline">
                                    <i class="ri-attachment-2"></i> Lihat Lampiran
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div id="reply-area" class="bg-white rounded-xl shadow-lg border border-indigo-100 p-6 mt-8">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="ri-chat-1-line text-indigo-500"></i> Balas Pesan
        </h3>
        
        <form action="<?= BASEURL ?>/admin/messages/store" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="parent_id" value="<?= $message['id'] ?>">
            
            <?php 
                $replyToId = ($message['sender_id'] == $_SESSION['user_id']) ? $message['recipient_id'] : $message['sender_id'];
            ?>
            <input type="hidden" name="recipient_id" value="<?= $replyToId ?>">
            <input type="hidden" name="subject" value="Re: <?= htmlspecialchars($message['subject']) ?>">

            <div class="mb-4">
                <textarea name="body" rows="4" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition resize-none" placeholder="Tulis balasan Anda..."></textarea>
            </div>

            <div class="flex justify-between items-center">
                <div class="w-1/2">
                    <label class="cursor-pointer inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition">
                        <i class="ri-attachment-line text-lg"></i>
                        <span class="font-medium">Lampirkan File</span>
                        <input type="file" name="attachment" class="hidden" onchange="document.getElementById('fileName').textContent = this.files[0].name">
                    </label>
                    <span id="fileName" class="ml-2 text-xs text-gray-400 truncate max-w-[150px] inline-block align-bottom"></span>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="ri-send-plane-fill"></i> Kirim Balasan
                </button>
            </div>
        </form>
    </div>
</div>