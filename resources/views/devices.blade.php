<html>
    <head>
        <title>RoboHome | Devices</title>
        <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#edit-device-modal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);

                    var devicePublicId = button.data('device-public-id');
                    var deviceName = button.data('device-name');
                    var deviceDescription = button.data('device-description');
                    var deviceOnCode = button.data('device-on-code');
                    var deviceOffCode = button.data('device-off-code');
                    var devicePulseLength = button.data('device-pulse-length');

                    var modal = $(this);

                    modal.find('#device-update-form').attr('action', '/devices/update/' + devicePublicId)
                    modal.find('#device-name-input').val(deviceName);
                    modal.find('#device-description-input').val(deviceDescription);
                    modal.find('#device-on-code-input').val(deviceOnCode);
                    modal.find('#device-off-code-input').val(deviceOffCode);
                    modal.find('#device-pulse-length-input').val(devicePulseLength);
                })
            });

            function controlDevice(action, publicDeviceId) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "/devices/" + action + "/" + publicDeviceId
                });
            }
        </script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <nav class="navbar navbar-default" role="navigation">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                 <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                            </button><a class="navbar-brand" href="#">RoboHome</a>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    @include('partials.flash-messages')
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             <div class="panel-title" dusk="devices-table-header">
                                 {{ $name }}'s Controllable Devices
                             </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-condensed" dusk="devices-table">
                                @foreach ($devices as $device)
                                    <tr>
                                        <td class="col-xs-1">
                                            <div class="dropdown" dusk="modify-device-dropdown">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Edit Device" dusk="modify-device-button">
                                                    <span class="glyphicon glyphicon-cog"></span> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="#edit-device-modal" aria-label="Edit Device" data-toggle="modal" data-target="#edit-device-modal" data-device-public-id="{{ $device->public_id }}" data-device-name="{{ $device->name }}" data-device-description="{{ $device->description }}" @foreach($device->htmlDataAttributesForSpecificDevice() as $property) {{ $property }} @endforeach>
                                                            <span class="glyphicon glyphicon-pencil"></span> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a id="delete" href="/devices/delete/{{ $device->public_id }}">
                                                            <span class="glyphicon glyphicon-remove"></span> Delete
                                                        </a>
                                                        <form id="delete-form" action="/devices/delete/{{ $device->public_id }}" method="GET" style="display: none;">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="col-xs-6">
                                            <div>
                                                {{ $device->name }}
                                            </div>
                                            <div>
                                                <small>{{ $device->description }}</small>
                                            </div>
                                        </td>
                                        <td class="col-xs-5">
                                            <div class="btn-group pull-right" role="group" aria-label="Device Controls">
                                                <button type="button" class="btn btn-primary" onclick="controlDevice('turnon', '{{ $device->public_id }}');">On</button>
                                                <button type="button" class="btn btn-primary" onclick="controlDevice('turnoff', '{{ $device->public_id }}');">Off</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="panel-footer">
                            <button type="button" class="btn btn-default" aria-label="Add Device" data-toggle="modal" data-target="#add-device-modal" dusk="open-add-device-button-modal">
                                <span class="glyphicon glyphicon-plus"></span> Add Device
                            </button>
                            @include('partials.add-device-modal')
                            @include('partials.edit-device-modal')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        $( "#delete" ).click(function(event) {
            event.preventDefault();
            swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover this device!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                document.getElementById('delete-form').submit();
              } else {
                swal("Your Device is safe!");
              }
            });
        });
    </script>
</html>
