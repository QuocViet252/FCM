@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="header">
        <div class="group-list">
            <ul>
                @foreach($group as $key => $value)
                <li>
                    <a href="{{url('group/'.$key)}}" class="btn-send-message">Group : {{$value['name']}}</a>
                </li>
                @endforeach
            </ul>
            <div class="create-group">
                <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Create
                    Group</button>
            </div>
        </div>
    </div>

</div>

</div>
<div id="id01" class="modal">

    <form class="modal-content animate" action="{{url('create-group')}}" method="post">
        @csrf
        <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close"
                title="Close Modal">&times;</span>
        </div>

        <div class="container-form">
            <label for="groupname"><b>Name Group</b></label>
            <input type="text" placeholder="Enter name group" name="groupname" required>

            <label for="psw"><b>Member</b></label>

            <select multiple="multiple" name="name_member">
                @foreach($user as $value)
                <option value="{{$value->id}}">{{$value->name}}</option>
                @endforeach
            </select>

            <button type="submit">Create</button>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button type="button" onclick="document.getElementById('id01').style.display='none'"
                class="cancelbtn">Cancel</button>
        </div>
    </form>
</div>
<!-- <script>

    const messaging = firebase.messaging();
    function sendTokenToSever(token_fcm) {
        const user_id = '{{Auth::user()->id}}';

        axios.post('api/save-token', {
            token_fcm, user_id
        }).then(res => {
            console.log(res);
        });
    }
    function retrieveToken() {
        messaging.getToken({ vapidKey: 'BF0z6_D9MuZgajUA3_eL8EbPVfhCjjU-e_NKp3i5wNgjxSfOmH8fotyAeLnVQqMcEHH2lg7rsWIipF8z-DGmsW8' }).then((currentToken) => {
            if (currentToken) {
                console.log(currentToken);
                // Send the token to your server and update the UI if necessary
                sendTokenToSever(currentToken);
            } else {
                // Show permission request UI
                // console.log('No registration token available. Request permission to generate one.');
                // ...
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            // ...
        });

    }
    retrieveToken();
    messaging.onTokenRefresh(() => {
        retrieveToken();
    });
    messaging.onMessage((payload) => {
        console.log('done');
        console.log(payload);
        location.reload();
    });

</script> -->
@endsection
@section('script')
<script>
    // Get the modal
    var modal = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection
