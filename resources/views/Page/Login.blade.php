@extends('layouts.app')

@section('head')
    <title>Login</title>
@endsection

@section('content')
   <x-header class="flex justify-center items-center h-44">
      <img src="{{asset('assets/image/logo.png')}}" />
   </x-header>

   <x-content>
      <p class="text-gray-500 text-center my-2 text-sm md:text-lg lg:text-xl xl:text-2xl">
         Silahkan login menggunakan akun karyawan atau perusahaan
      </p>
      <div class="flex flex-col mx-auto w-10/12 md:w-12/12">
         <form id="form">
            <div class="mb-4">
               <label for="email">Email</label>
               <input type="text" name="email" id="email" class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0" placeholder="Masukan email atau NIP" />
            </div>
            <div class="mb-4">
               <label for="password">Password</label>
               <input type="password" name="password" id="password" class="p-2.5 block mt-1 w-full rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0" placeholder="Masukan Password" />
            </div>
         </form>
         {{-- <a href="#" class="text-sm">Lupa Password ?</a> --}}
         <x-button class="bg-[#44B156] text-white" id="login">Login</x-button>
      </div>
   </x-content>

@endsection

@section('javascript')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
   $("#login").click(function(){
      var email = $("#email").val();
      var password = $("#password").val();

      if(email.trim() == "" || password.trim() == "") {
         return swal('Kesalahan!', 'Email dan password tidak boleh kosong!', 'error');
      }

      var form = $("#form").serialize();
      $.ajax({
         url: "{{route('mobile.auth.loginAction')}}",
         method: 'POST',
         data: form,
         success: function(data) {
            if(data.status == 'error') {
               return swal(data.title, data.message, data.status);
            }
            window.location = '/views/home';
         }
      })
   });
})
</script>
@endsection