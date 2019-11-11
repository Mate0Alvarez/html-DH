/**
 * ID of the current conversation.
 */
conversationId = null;

/**
 * The last message ID retrieved.
 */
lastMessageId = null;

/**
 * Runs on document load.
 */
$(document).ready(function() {
	var input = document.getElementById('input');
	
	input.addEventListener('keydown', function(event) {
		checkEnter(event, 'say');
	});
	
	if ('webkitSpeechRecognition' in window) {
		enableSpeechRecognition();
	}
	
	focusInput();
	updateStatsLoop();

	if (docCookies.getItem('warningHidden') !== 'true') {
		document.getElementById('warning-hide-button').addEventListener('click', hideChatWarning);
		showChatWarning();
	}
});

/**
 * Scrolls a div to the bottom.
 * 
 * @param {object} div DOM object
 * @return {void}
 */
function scrollToBottom(div)
{
	div.scrollTop = div.scrollHeight;
	div.scrollTop = div.scrollHeight;
}

/**
 * Checks if enter has been pressed, and executes a function if it was.
 * 
 * @param e Event
 * @param {string} func Function to execute
 */
function checkEnter(e, func)
{
	var keyCode = e ? (e.which ? e.which : e.keyCode) : e.keyCode;
	if(keyCode == 13) { eval(func+'();'); }
}

/**
 * Appends messages to the chatbox.
 * 
 * @param {object} data Messages array
 * @return {void}
 */
function appendMessages(data)
{
	// Get template
	$.get(config.domainRoot+'templates/messages.mst', function(template) {
		// Set author booleans
		$.each(data.messages, function(index, message) {
			if (message.author_id === 0) {
				data.messages[index].robot = true;
			}
			else {
				data.messages[index].human = true;
			}
		});
		
		// Render data using template
		var rendered = Mustache.render(template, data);

		// Append message to chat
		var div = document.getElementById('messages');
		div.innerHTML = div.innerHTML + rendered;

		// Scroll chat to bottom
		scrollToBottom(div);
	});
}

/**
 * Says a message as the user.
 * 
 * @return {boolean}
 */
function say()
{
	// If a conversation hasn't been started yet
	if (conversationId === null)
	{
		// Start a new conversation
		newConversation();
	}
	
	// Filter input
	var input = document.getElementById('input').value.replace(/^\s+|\s+$/g, '');

	// If input is empty
	if (input === '')
	{
		return false;
	}

	// Clear message input and focus on it
	var messageInput = document.getElementById('input');
	messageInput.value = '';
	messageInput.focus();

	// Build data to send
	$data = {
		"conversation_id": conversationId,
		"input": input
	};

	// Post data to server
	$.post(config.domainRoot+'ajax/say', $data, function(response) {
		// Update
		getLog(function() {
			// Let Botster respond
			think();
		});
		updateStat('conversations');
	});
}

/**
 * Lets botster respons to conversation.
 */
function think()
{
	$.post(config.domainRoot+'ajax/think', function(response) {
		// Update log
		getLog(function(){});
		
		// Update stats
		updateStat('utterances');
		updateStat('connections');
	});
}

/**
 * Gets any new messages in the conversation and appends them to the chatbox.
 * 
 * @param {function} callback Function to call when done
 * @return {void}
 */
function getLog(callback)
{
	$url = config.domainRoot+'ajax/log';
	
	// If last message ID has been set
	if (lastMessageId !== null)
	{
		// Append last message ID as GET variable
		$url = $url+'?last='+lastMessageId;
	}
	
	$.getJSON($url, function(data) {
		// If there are new messages
		if (data.messages.length !== 0)
		{
			// Add new messages
			appendMessages(data);

			// Update last message ID
			lastMessageId = data.messages[data.messages.length-1].id;
		}
		
		callback();
	});
}

/**
 * Starts a new conversation.
 * 
 * @return {void}
 */
function newConversation()
{
	var request = new XMLHttpRequest();
	request.onload = function() {
		// Decode data
		var data = JSON.parse(request.responseText);
		
		// Set conversation ID
		conversationId = data.conversation_id;
	};
	request.open('get', config.domainRoot+'ajax/conversations/create', false);
	request.send();
}

/**
 * Focuses on the message input field.
 * 
 * @return {void}
 */
function focusInput()
{
	document.getElementById('input').focus();
}

/**
 * Starts a loop which updates all stats every 10 seconds.
 * 
 * @return {void}
 */
function updateStatsLoop()
{
	updateStat('online');
	updateStat('conversations');
	updateStat('utterances');
	updateStat('connections');
	
	setTimeout("updateStatsLoop()", 10000);
}

/**
 * Updates a statistic on the page.
 * 
 * @param {string} name Name of the statistic
 * @returns {void}
 */
function updateStat(name)
{
	$.get(config.domainRoot+'ajax/'+name, function(data) {
		$("#"+name).html(data);
	});
}

/**
 * Shows the chat warning.
 * 
 * @returns {void}
 */
function showChatWarning() {
	document.getElementById('chat-warning').style.display = 'block';
}

/**
 * Hides the chat warning.
 * 
 * @returns {void}
 */
function hideChatWarning() {
	document.getElementById('chat-warning').style.display = 'none';
	docCookies.setItem('warningHidden', true, 'Fri, 31 Dec 9999 23:59:59 GMT', '/');
	focusInput();
}

/**
 * Displays the speech recognition input button.
 * 
 * @returns {void}
 */
function enableSpeechRecognition() {
	var speechInput = document.getElementById('speech-input');
	document.getElementById('input').style.paddingRight = '50px';
	speechInput.style.display = 'block';
	speechInput.addEventListener('click', getSpeechInput);
}

/**
 * Requests the user for a speech input.
 * 
 * @returns {void}
 */
function getSpeechInput() {
	var recognition = new webkitSpeechRecognition();
	recognition.onaudiostart = function() {
		document.getElementById('speech-input').src = config.domainRoot+'images/icons/red_microphone.png';
	};
	recognition.onaudioend = function() {
		document.getElementById('speech-input').src = config.domainRoot+'images/icons/microphone.png';
	};
	recognition.onresult = function(event) {
		document.getElementById('input').value =  event.results[0][0].transcript;
		focusInput();
	};
	recognition.start();
}
