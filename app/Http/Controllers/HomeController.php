<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chat;
use Auth;
use App\User;
use App\Text;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Group;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Database;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Firestore;


class HomeController extends Controller
{
    // public function __construct(Database $database)
    // {
    //     $this->database = $database;
    //     $this->tablename = 'group';
    // }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }
    public function test()
    {

        $test = app('firebase.firestore')->database()->collection('Text')->newDocument();
        $test->set([
            'a' => 'viet',
            'b' => 'tran',
        ]);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $group = $this->database->getReference($this->tablename)->getValue();
        // dd($group);
        $user = User::all();
        return view('home', compact('user', 'group'));
    }
    public function createChat(Request $request, $id)
    {
        $key = $id;
        $message = $_POST['title'];
        $postData = [
            'sender_id' => Auth::user()->id,
            'sender_name' => Auth::user()->name,
            'text' => $message,
        ];
        $send_text = $this->database->getReference($this->tablename . '/' . $key) // this is the root reference
            ->update($postData);
        if ($send_text) {
            $this->broadcastMessage(Auth::user()->name, $message);
        }
    }

    private function broadcastMessage($senderName, $message)
    {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New message from : ' . $senderName . '');
        $notificationBuilder->setBody($message)
            ->setSound('default')
            ->setClickAction('http://127.0.0.1:8000/group/');
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'sender_name' => $senderName,
            'message' => $message,
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = User::all()->pluck('token_fcm')->toArray();
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        return $downstreamResponse->numberSuccess();
    }
    public function createGroup(Request $request)
    {
        $postData = [
            'id_add' => $request->name_member,
            'id_create' => Auth::user()->id,
            'sender_id' => "",
            'sender_name' => "",
            'text' => "",
            'name' => $request->groupname,
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        if ($postRef) {
            return redirect()->back();
        }
    }
    public function getGroup($id)
    {
        $id_group = $id;
        $group = $this->database->getReference($this->tablename)->getValue();
        $group_edit = $this->database->getReference($this->tablename)->getChild($id_group)->getValue();
        $user = User::all();

        return view('group', compact('group', 'user', 'id_group', 'group_edit'));
    }
    public function sendText()
    {
        // dd($_POST['value']);
    }
}
