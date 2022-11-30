@extends('admin.layouts.main')

@section('container')
<div class="md:shadow-md bg-white rounded-lg p-3 pt-4">
  <div class="flex flex-wrap md:flex-nowrap gap-3 justify-between items-center p-4 pt-1">
    <a href="/admin/profil/sejarah" class="font-bold text-xl text-blue-kominfo inline-flex items-center">
      <svg 
        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
        <polyline points="15 18 9 12 15 6"></polyline>
      </svg>
      Edit Sejarah
    </a>
  </div>
  <hr>
  <form id="form-upload" class="p-5" action="/admin/layanan" method="post" enctype="multipart/form-data"> 
    @method ('put')
    @csrf
    <div class="mb-5">
        <x-admin.input-upload infoFileReadOnly="{!! $imgSejarah1 !!}"  type="image" input-name="File Gambar 1" form-name="imgSejarah1" :is-filled=true :is-error=false >
            hanya file gambar (.jpg, .jpeg, .png, etc ) max 10mb.
        </x-admin.input-upload>
    </div>
    <div class="mb-5">
        <x-admin.input-upload  infoFileReadOnly="{!! $imgSejarah2 !!}"  type="image" input-name="File Gambar 2" form-name="imgSejarah2" :is-filled=true :is-error=false >
            hanya file gambar (.jpg, .jpeg, .png, etc ) max 10mb.
        </x-admin.input-upload>
    </div>
    <div class="mb-5">
        <x-admin.input-upload  infoFileReadOnly="{!! $imgSejarah3 !!}"  type="image" input-name="File Gambar 3" form-name="imgSejarah3" :is-filled=true :is-error=false >
            hanya file gambar (.jpg, .jpeg, .png, etc ) max 10mb.
        </x-admin.input-upload>
    </div>
    <div class="mb-5">
        <x-admin.body-editor input-value="{!! $sejarahBody !!}" input-name="Body" form-name="sejarahBody" />
    </div>
    <div class="mb-6">
      <x-admin.form-button btn-name="send" isAjax={{ true }}>
        Edit
      </x-admin.form-button>
    </div>  
  </form>
</div>
    
@endsection


@push('upload-script') 
  <script>

    // ----------- UPLOAD ------------
    let form = document.querySelectorAll('form#form-upload')[0];

    form.addEventListener('submit', (e)=>{
      e.preventDefault();
      upload()
    })

    function upload(){
      let data =  new FormData(form);
      data.set('_token', '{{csrf_token()}}');
      data.set('_method', 'PUT')
      
      fetch(window.location.href, {
        method: 'POST',
        headers:{
          "X-CSRF-Token": "{{ csrf_token() }}",
          'Accept': 'application/json',
        },
        mode: 'same-origin',
        body: data
      })
      .then(async res =>[res.status, await res.json()])
      .then(result => {
        console.log(result)
        if(result[1].success){
          window.location = document.referrer;
        }else if(res[0] == 422){
          form_name_text.error(true, res[0].errors.name); 
        }
      })
      .catch(err => console.error(err))
    }
  </script>
@endpush