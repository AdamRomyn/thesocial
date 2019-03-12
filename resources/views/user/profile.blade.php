@extends('layouts.app')

@section('content')
    <div class="container profile-content">
        <div class="alert alert-info hidden top-alert"></div>
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-8">
                <div class="card">
                    <div class="card-header profile-pic">
                        <img class="profile-img" src="{{ URL::asset("img/person.png") }}" />
                        @if(Auth::user()->id == $user->id)
                            <div class="settings">
                                <button id="edit-user" class="btn btn-lg">
                                    Edit Details
                                </button>
                                <button id="change-user-password" class="btn btn-lg">
                                    Change password
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 friends-sidebar">

                                <h3>{{$user->name}} Friends</h3>
                                @if(sizeof($friends) < 1)
                                    <p>{{ $user->name }} has no friends.</p>
                                @endif
                                @foreach($friends as $friend)
                                    <div class="card card-friend">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {{ $friend->name }}
                                                </div>
                                                <div class="col-md-8">
                                                    <a class="btn btn-primary float-right" href="{{ url('/profile/'.$friend->id) }}">
                                                        View Profile
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-7">
                                <form id="user-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Name:</h5>
                                            <div class="form-group" id="name-group">
                                                <label for="name">{{ $user->name }}</label>
                                                <input type="text" class="form-control hide" id="name" value="{{$user->name}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Email:</h5>
                                            <div class="form-group" id="email-group">
                                                <label for="email">{{ $user->email }}</label>
                                                <input type="text" class="form-control hide" id="email" value="{{$user->email}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-success float-right hide" id="submit-user-details">Submit</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 password-section">
                                            <h5>Password</h5>
                                            <div class="form-group">
                                                <input type="password" class="form-control edit mb-3" id="password-old" placeholder="Old password"/>
                                                <input type="password" class="form-control edit" id="password-new" placeholder="New password"/>
                                                <button id="change-password" class="btn btn-success float-right mt-4">Change Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        jQuery(document).ready(function(){
            function setAlertText(text){
                var alert = $(".top-alert");
                alert.removeClass("hidden");
                alert.removeClass("alert-danger");
                alert.addClass("alert-info");
                alert.text(text);
            };

            function setErrorText(text){
                var alert = $(".top-alert");
                alert.removeClass("hidden");
                alert.removeClass("alert-info");
                alert.addClass("alert-danger");
                alert.text(text);
            }

            var togglePassword = function (){
                var changePasswordButton = "#change-user-password";
                if($(changePasswordButton).hasClass("edit")){
                    $(changePasswordButton).text("Edit Details");
                } else {
                    $(changePasswordButton).text("Cancel");
                }

                $(".password-section").toggleClass("edit");
                $(changePasswordButton).toggleClass("edit");
            };

            $('#change-user-password').click(function(e){
                togglePassword(e);
            });

            $('#change-password').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/user/change_password') }}",
                    method: 'post',
                    data: {
                        old_password: $("#password-old").val(),
                        new_password: $("#password-new").val()
                    },
                    success: function(result){
                        //do something
                        console.log(result);
                        setAlertText("Your password was changed successfully.");
                        togglePassword();
                    },
                    error: function(result){
                        setErrorText("You did not enter your old password correctly.");
                    }
                });
            });

            var toggleEdit = function (){
                var changePasswordButton = "#edit-user";
                if($(changePasswordButton).hasClass("edit")){
                    $(changePasswordButton).text("Change password");
                } else {
                    $(changePasswordButton).text("Cancel");
                }

                $(changePasswordButton).toggleClass("edit");
                $("input#email").toggleClass("edit");
                $("input#name").toggleClass("edit");
                $("#name-group label").toggleClass("hide");
                $("#email-group label").toggleClass("hide");
                $("#submit-user-details").toggleClass("hide");
            };

            $('#edit-user').click(function(e){
                toggleEdit();
            });

            $("#submit-user-details").click(function (e) {
                e.preventDefault();
                let name = $("input#name").val();
                let email = $("input#email").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/user/change_details') }}",
                    method: 'post',
                    data: {
                        name: name,
                        email: email
                    },
                    success: function(result){
                        //do something
                        $("#name-group label").text(name);
                        $("#email-group label").text(email);
                        setAlertText("Your details was changed successfully.");
                        toggleEdit();
                    },
                    error: function(result){
                        setErrorText("There was an error try again later");
                    }
                });
            });
        });
    </script>
@endsection
