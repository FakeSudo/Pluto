<?php
namespace FakeSudo\Pluto\Telegram;

class Router{
        
    /**
     * Update
     *
     * @var mixed
     */
    private $Update;
    
    /**
     * UpdateType
     *
     * @var mixed
     */
    private $UpdateType;
    
    /**
     * __construct
     *
     * @param  mixed $Update
     * @return void
     */
    public function __construct($Update)
    {
        $this->Update = $Update;
        $this->UpdateType = $Update->getUpdateType();
        if($this->UpdateType == "message"){
            $this->UpdateType = $Update->getMessage()->getType();
        }
    }
    
    /**
     * exec
     *
     * @param  mixed $Controller
     * @param  mixed $Update
     * @return void
     */
    private function exec($Controller, $Update) {
        $NameSpace = '\\' . $Controller;
        $Executor = new $NameSpace;
        $Executor->index($Update);
    }
    
    /**
     * any
     *
     * @param  mixed $Controller
     * @return void
     */
    public function any($Controller){
        $this->exec($Controller,$this->Update);
    }

    public function match($Types = [], $Controller){
        if(array_key_exists($this->MessageType,$Types)){
            $this->exec($Controller,$this->Update);
            exit();
        }
    }
    
    /**
     * Command
     *
     * @param  mixed $Route
     * @param  mixed $Controller
     * @return void
     */
    public function Command(string $Route, string $Controller) {
        if($this->MessageType == "command" && preg_match("/^(\/$Route)/", $this->Update->getMessage()->getText())){
            $this->exec($Controller,$this->Update->getMessage());
            exit();
        }
    }
    
    /**
     * Commands
     *
     * @param  mixed $params
     * @return void
     */
    public function Commands(array $params) {
        foreach($params as $Route => $Controller) {
            $this->Command($Route, $Controller);
            exit();
        }
    }
    
    /**
     * Text
     *
     * @param  mixed $Route
     * @param  mixed $Controller
     * @return void
     */
    public function Text(string $Route, string $Controller) {
        if($this->MessageType == "text" && preg_match("/^($Route)/", $this->Update->getMessage()->getText())){
            $this->exec($Controller,$this->Update->getMessage());
            exit();
        }
    }
    
    /**
     * Texts
     *
     * @param  mixed $params
     * @return void
     */
    public function Texts(array $params) {
        foreach($params as $Route => $Controller) {
            $this->Text($Route, $Controller);
            exit();
        }
    }
    
    /**
     * InlineQuery
     *
     * @param  mixed $Route
     * @param  mixed $Controller
     * @return void
     */
    public function InlineQuery(string $Route, string $Controller) {
        if($this->UpdateType == 'inline_query' && preg_match("/^($Route)/", $this->Update->getInlineQuery()->getQuery())){
            $this->exec($Controller,$this->Update->getInlineQuery());
            exit();
        }
    }
    
    /**
     * InlineQueries
     *
     * @param  mixed $params
     * @return void
     */
    public function InlineQueries(array $params) {
        foreach($params as $Route => $Controller) {
            $this->InlineQuery($Route, $Controller);
            exit();
        }
    }
    
    /**
     * CallBackQuery
     *
     * @param  mixed $Route
     * @param  mixed $Controller
     * @return void
     */
    public function CallBackQuery($Route,$Controller){
        if($this->UpdateType == "callback_query"&& preg_match("/^($Route)/", $this->Update->getCallbackQuery()->getData())){
            $this->exec($Controller,$this->Update->getCallbackQuery());
            exit();
        }
    }
    
    /**
     * CallBackQueries
     *
     * @param  mixed $params
     * @return void
     */
    public function CallBackQueries(array $params) {
        foreach($params as $Route => $Controller) {
            $this->CallBackQuery($Route, $Controller);
            exit();
        }
    }
    
    /**
     * MessageTypes
     *
     * @var array
     */
    private $MessageTypes = [
        'Audio' => 'audio',
        'Video' => 'video',
        'Animation' => 'animation',
        'Document' => 'document',
        'Game' => 'game',
        'Photo' => 'photo',
        'Sticker' => 'sticker',
        'Voice' => 'voice',
        'VideoNote' => 'video_note',
        'Contact' => 'contact',
        'Location' => 'location',
        'Venue' => 'venue',
        'Poll' => 'poll',
        'NewChatMembers' => 'new_chat_members',
        'LeftChatMember' => 'left_chat_member',
        'NewChatTitle' => 'new_chat_title',
        'NewChatPhoto' => 'new_chat_photo',
        'DeleteChatPhoto' => 'delete_chat_photo',
        'GroupChatCreated' => 'group_chat_created',
        'MessageAutoDeleteTimerChanged' => 'message_auto_delete_timer_changed',
        'MigrateToChatId' => 'migrate_to_chat_id',
        'MigrateFromChatId' => 'migrate_from_chat_id',
        'PinnedMessage' => 'pinned_message',
        'invoice' => 'invoice',
        'SuccessfulPayment' =>  'successful_payment',
        'PassportData' => 'passport_data',
        'ProximityAlertTriggered' => 'proximity_alert_triggered',
        'VideoChatScheduled' => 'video_chat_scheduled',
        'VideoChatStarted' => 'video_chat_started',
        'VideoChatEnded' => 'video_chat_ended',
        'VideoChatParticipantsInvited' => 'video_chat_participants_invited',
        'WebAppData' => 'web_app_data',
        'ReplyMarkup' => 'reply_markup',
        
    ];
    
    /**
     * UpdateTypes
     *
     * @var array
     */
    private $UpdateTypes = [
        'EditedMessage' => 'edited_message',
        'ChannelPost' => 'channel_post',
        'EditedChannelPost' => 'edited_channel_post',
        'ChosenInlineResult' => 'chosen_inline_result',
        'MyChatMember ' => 'my_chat_member',
        'ChatMember' => 'chat_member',
        'ChatJoinRequest' => 'chat_join_request'
    ];
    
    /**
     * __call
     *
     * @param  mixed $name
     * @param  mixed $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        if(array_key_exists($name,$this->MessageTypes)){
            if($this->UpdateType == $this->MessageTypes[$name]){
                $this->exec($arguments[0],$this->Update->getMessage());
                exit();
            }
        }else{
            if($this->UpdateType == $this->UpdateTypes[$name]){
                $this->exec($arguments[0],$this->Update);
                exit();
            }
        }
    }

    
}