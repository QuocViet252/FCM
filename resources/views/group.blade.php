@extends('layouts.app')

@section('content')

<div class="wrapper">

    <div class="header">
        <div class="group-list">
            <ul>
                @foreach($group as $key => $value)
                @if($key == $id_group)
                <li>
                    <a style="background-color: blue;" href="{{url('group/'.$key)}}" class="btn-send-message">Group :
                        {{$value['name']}}</a>
                </li>
                @else
                <li>
                    <a href="{{url('group/'.$key)}}" class="btn-send-message">Group : {{$value['name']}}</a>
                </li>
                @endif
                @endforeach

            </ul>
            <div class="create-group">
                <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Create
                    Group</button>
            </div>
        </div>
    </div>
    <div class="content-chat">

        <b class="sender_name">{{$group_edit['sender_name']}}</b><br>
        <p class="chat chat-left">{{$group_edit['text']}}</p>
        <p class="hide test-notice-show">Ai đó đang chỉnh sửa</p>

    </div>
    <div class="input-message">
        <form id="test-form" action="{{url('group/'.$key)}}" method="post">
            @csrf
            <div class="content-message">
                <input type="hidden" value="" name="id_group">
                <input id="test" type="text" name="message" class="form-control" required placeholder="Message....">
            </div>
            <!-- <div class="button-message">
                <button type="submit" class="btn-send-message">SEND</button>
            </div> -->
        </form>
    </div>

</div>
<div id="id01" class="modal">

    <form class="modal-content animate" action="{{url('create-group')}}" method="post">
        @csrf
        <div class="container-form">
            <label for="groupname"><b>Name Group</b></label>
            <input type="text" placeholder="Enter name group" name="groupname" required>

            <label for="psw"><b>Member</b></label>

            <select multiple="multiple" name="name_member[]">
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

@endsection
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    const messaging = firebase.messaging();
    function sendTokenToSever(token_fcm) {

        const user_id = '{{Auth::user()->id}}';

        axios.post('/api/save-token/.$id_group', {
            token_fcm, user_id
        }).then(res => {
            // console.log(res);
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
    $(document).ready(function () {

        $(".test-notice-show").hide();

        $("#test").on("input", function (e) {
            var x = $(e.target).val();
            var id_text = $(".id_text").val();
            if (x) {
                $.ajax({
                    url: '{{url("/group/$id_group")}}',
                    type: 'POST',
                    data: { "title": x, "id_text": id_text },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function (response) {//kết quả trả về từ server nếu gửi thành công
                        if (response) {
                            // console.log(response)
                        }
                    }
                });
            }

        });
        messaging.onMessage((payload) => {
            // console.log('done');
            if (payload) {
                $(".test-notice-show").show().removeClass('hide');
                setTimeout("$('.test-notice-show').hide().addClass('hide');", 5000);
                console.log(payload);
                $.map(payload, function (n, index) {

                    $(".chat").text(n['message']);
                    $(".sender_name").text(n['sender_name']);

                });

                // $(".test-notice-show").hide();

            }





        });

    });
</script>
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

<!-- <script>

</script> -->

@endsection
