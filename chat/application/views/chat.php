<div class="app">
    <div class="warning" id="chat-warning">
        <div class="message">
            <img src="<?=URL::site('images/icons/cross.png')?>" alt="Hide" title="Close" id="warning-hide-button" class="hide-button" />
            <div class="title">Warning</div>
            <div class="text">Botster learns from real people on the Internet and as a result, may sometimes use foul or inappropriate language.</div>
        </div>
    </div>
    <div class="container">
        <div class="statistics">
            <div title="People chatting" class="statistic">
                <img src="<?=URL::site('images/icons/user.png')?>" alt="People chatting: " class="icon" />
                <div id="online" class="number"></div>
            </div>
            <div title="Conversations had" class="statistic">
                <img src="<?=URL::site('images/icons/speech_bubbles.png')?>" alt="" class="icon" />
                <div id="conversations" class="number"></div>
            </div>
            <div title="Unique utterances" class="statistic">
                <img src="<?=URL::site('images/icons/quote.png')?>" alt="" class="icon" />
                <div id="utterances" class="number"></div>
            </div>
            <div title="Utterance connections" class="statistic">
                <img src="<?=URL::site('images/icons/link.png')?>" alt="" class="icon" />
                <div id="connections" class="number"></div>
            </div>
        </div>
        <div class="chat">
            <div id="messages" class="messages"></div>
            <div class="input">
                <input type="text" maxlength="100" autocomplete="off" placeholder="Type a message..." id="input" class="text-box" />
                <img src="<?=URL::site('images/icons/microphone.png')?>" alt="Speech input" title="Speech input" id="speech-input" class="speech-input" />
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=URL::site('javascript/chat.js')?>"></script>
