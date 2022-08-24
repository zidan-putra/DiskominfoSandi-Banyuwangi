

<!-- detail modal -->
<div id="modal-detail" tabindex="-1" class="bg-[rgba(100,116,139,0.3)] overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full inset-0 h-modal h-full justify-center items-center flex transition-all opacity-0 hidden" aria-modal="true" role="dialog">
    <div class="relative p-4 w-full max-w-2xl h-auto max-h-[75vh] overflow-y-auto">
        <!-- Modal content -->
        <div class="modal-detail-content relative overflow-y-auto bg-white rounded-lg shadow">
            <!-- Modal header dan body -->
            {{ $slot }}
            <!-- Modal footer -->
            <div class="flex items-center p-6 mx-5 space-x-2 rounded-b border-t border-gray-200">
                <button data-modal-toggle="defaultModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center ">Save</button>
                <button data-modal-toggle="defaultModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-md border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 close-modal-detail">Close</button>
            </div>
        </div>
    </div>
</div>

@push('add-script')
<script>
    let openModal = document.querySelectorAll('.btn-detail'); //button untuk mentrigger kemunculan modal
    let modalDetail = document.querySelector('#modal-detail'); //semmua modalnya
    let modalContentDetail = modalDetail.querySelector('.modal-detail-content'); //wrapper untuk modal header dan body
    let modalClose = document.querySelector('.close-modal-detail'); //tombol untuk menutup modal
    let modalHeader = modalDetail.querySelectorAll('.modal-detail-content > div')[0];
    let modalBody = modalDetail.querySelectorAll('.modal-detail-content > div')[1];
    let modalData;
    let modalField =[]; //data yang diekstrak dari elemen modal detail
    let userData = {}; //ini hanya digunakan untuk menapikan tabel user pada berita

    const para = document.createElement("p");
    const node = document.createTextNode("This is new.");
    para.appendChild(node);
    console.log(para)

    //------------ function untuk toggle modal -----------
    function toggleModal(){
        if(modalDetail.classList.contains('hidden')){
            modalDetail.classList.remove('hidden');
            setTimeout(() => {
                modalDetail.classList.remove('opacity-0');
            }, 10);
        }else{
            modalDetail.classList.add('opacity-0');
            setTimeout(() => {
                modalDetail.classList.add('hidden');
            }, 350);
        }
    }

    //------------ menutup modal ----------------
    modalClose.addEventListener('click', ()=> toggleModal());
    modalDetail.addEventListener('click',(e)=> e.target == modalDetail? toggleModal() : false);

    //function untuk mentukan banyak field yg tersedia, lalu menyimpanya menjadi sebuah array
    //digunakna untuk mempermudah filter
    function defineField(){
        let keysElement = modalContentDetail.querySelectorAll('.fill-detail');
        // console.log(keysElement)
        // console.log(modalContentDetail)
        // console.log(modalDetail)
        keysElement.forEach(el => {
            modalField.push({name:el.getAttribute('data-key'), element: el});
        })
        console.log(modalField)
    }
    defineField();
    

    // ----------- update  modal ---------------
    function updateDetailModal(data,model){
        // console.log(data)
        // console.log(modalField)
        // for(let key in data){
        //     modalContentDetail.querySelector(`[data-key=${key}]`) 
        //         ? modalContentDetail.querySelector(`[data-key=${key}]`).innerHTML = data[key]
        //         : ''
        // }

        switch(model){
            case 'dokumen':
                for(let key in data){
                    console.log(key)
                    modalField
                        .filter(el => el.name == key)
                        .forEach(el =>{
                            console.log(el)
                            el.element.innerHTML = data[key]
                            if(key == 'created_at') el.element.innerHTML = data[key].split('T')[0];
                        })
                }
                break;
            case 'galeri':
                for(let key in data){
                    console.log(key)
                    modalField
                        .filter(el => el.name == key)
                        .forEach(el =>{
                            console.log(el)
                            el.element.innerHTML = data[key]
                            if(key == 'created_at') el.element.innerHTML = data[key].split('T')[0];
                            if(el.element.hasAttribute('data-preview') && key == 'source' && data.type != 'video') el.element.innerHTML = `<img src="/storage/${data[key]}" />`
                            if(key == 'type'){
                                data[key] == 'image' ? el.element.innerHTML = `<p class="text-base  leading-relaxed bg-[#facc15] rounded-full px-3 mx-2">Image</p>` : 
                                data[key] == 'video' ? el.element.innerHTML = `<p class="text-base  leading-relaxed bg-[#71FF40] rounded-full px-3 mx-2">Video</p>` :
                                el.element.innerHTML = '<span class="text-red-500" >Ada yang error</span>'
                            }
                        })
                }
                break
            case 'berita':

                for(let key in data){
                    console.log(key)
                    modalField
                        .filter(el => el.name == key)
                        .forEach(el =>{
                            console.log(el)
                            if(key == 'author'){//karean pada field athor menggunakan return untuk menghentikan loop, maka sebaiknya author diletakan di paling bawah
                                userData.name.innerHTML = data[key].name
                                userData.username.innerHTML = data[key].username
                                userData.email.innerHTML = data[key].email
                                return;
                            }
                            el.element.innerHTML = data[key]
                            if(key == 'created_at') el.element.innerHTML = data[key].split('T')[0];
                            if(el.element.hasAttribute('data-preview') && key == 'image') el.element.innerHTML = `<img src="/storage/${data[key]}" />`
                            if(key == 'category'){
                                data[key] == 'berita' ? el.element.innerHTML = `<p class="text-base  leading-relaxed bg-[#facc15] rounded-full px-3 mx-2">Berita</p>` : 
                                data[key] == 'goverment' ? el.element.innerHTML = `<p class="text-base  leading-relaxed bg-[#71FF40] rounded-full px-3 mx-2">Goverment</p>` :
                                data[key] == 'technology' ? el.element.innerHTML = `<p class="text-base  leading-relaxed bg-[#0091ff] rounded-full px-3 mx-2">Technology</p>` :
                                el.element.innerHTML = '<span class="text-red-500" >Ada yang error</span>'
                            }
                            
                        })
                }
                break

            case 'link-terkait', 'layanan':
            for(let key in data){
                    console.log(key)
                    modalField
                        .filter(el => el.name == key)
                        .forEach(el =>{
                            el.element.innerHTML = data[key]
                            if(key == 'created_at') el.element.innerHTML = data[key].split('T')[0]
                        })
                }
                break
        }

    }

    //function untuk tabel user
    function defineUserTable(){
        if(typeof table != 'undefined'){
            let table = modalDetail.querySelector('#table-user')
            userData.name =  table.querySelector('#name-show')
            userData.username = table.querySelector('#username-show')
            userData.email = table.querySelector('#email-show')
        }
        console.log(userData)
    }
    defineUserTable();
    

    function getData (id){
        return fetch(`/admin/{{ $modelPath }}/${id}`)
            .then(res => res.json())
            .then(res => res)
            .catch(err => err)
    }

    openModal.forEach( btn => {
        btn.addEventListener('click', async function(e){
            try{
                modalData = await getData(this.getAttribute('data-id'));
                console.log(modalData);
                // console.log(this.getAttribute('data-id'));
                updateDetailModal(modalData[0],"{{ $modelPath }}");
                toggleModal();
            }catch(err){
                console.error(err);
            }
        });
    })
</script>
@endpush
