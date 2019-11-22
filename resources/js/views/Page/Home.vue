<template>
    
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title m-0">Pengguna</h4>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end page-title-box -->
        </div>
    </div> 
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-8">
                            <h4 class="mt-0 header-title">Semua Pengguna</h4>
                        </div>
                        
                        <div class="col-4">

                            <div class="float-right d-none d-md-block">
                                <div class="dropdown">
                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAdd">
                                        <i class="ti-plus mr-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {!! session('success') !!}
                        </div>
                        <div class="alert alert-danger">
                            <ul class='mb-0'>
                            </ul>
                        </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id='users-table'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Level</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="first_name">Nama depan</label>
                                <input id="first_name" type="text"
                                    class="form-control   " name="first_name" autofocus
                                    placeholder="Nama depan">
                                @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong> $errors->first('name') </strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group col-6">
                                <label for="last_name">Nama Belakang</label>
                                <input id="last_name" type="text" class="form-control  $errors->has('last_name') ? ' is-invalid' : '' "
                                    name="last_name" placeholder="Nama belakang">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control  $errors->has('email') ? ' is-invalid' : '' "
                                name="email" placeholder="Email">
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong> $errors->first('email') </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Username</label>
                            <input id="username" type="text" class="form-control  $errors->has('username') ? ' is-invalid' : '' "
                                name="username" placeholder="Username">
                            @if ($errors->has('username'))
                            <span class="invalid-feedback" role="alert">
                                <strong> $errors->first('username') </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group ">
                            <label for="password" class="d-block">Password</label>
                            <input id="password" type="password"
                                class="form-control pwstrength  $errors->has('password') ? ' is-invalid' : '' "
                                data-indicator="pwindicator" name="password">
                            <div id="pwindicator" class="pwindicator">
                                <div class="bar"></div>
                                <div class="label"></div>
                            </div>
                
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong> $errors->first('password') </strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Level</label>
                            <select name="level" id="" class="form-control">
                                <option value="Kasir">Kasir</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</template>
<script>
export default {
    
}
</script>

