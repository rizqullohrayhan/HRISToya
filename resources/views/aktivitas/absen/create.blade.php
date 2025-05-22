@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Absensi</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Absensi
                        </div>
                        <div class="card-category">Tambah absensi</div>
                    </div>
                    <div class="card-body">
                        <form id="add-form" action="{{ route('absen.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Username</label>
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->username }}" disabled/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('jenis') has-error @enderror">
                                <label for="jenis">Jenis <span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis" id="jenis">
                                    @foreach ($jenisAbsen as $jenis)
                                        <option value="{{$jenis->id}}" @selected($jenis->id == old('jenis'))>{{ $jenis->name }}</option>
                                    @endforeach
                                </select>
                                @error('jenis')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('kantor') has-error @enderror">
                                <label for="kantor">Kantor</label>
                                <input type="text" class="form-control" id="kantor" name="kantor" value="{{ Auth::user()->kantor->name }}" readonly/>
                                @error('kantor')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('location') has-error @enderror">
                                <label for="location">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{old('location')}}" placeholder="Terisi otomatis saat klik submit" readonly/>
                                <small class="form-text">
                                    Izinkan akses lokasi agar dapat melakukan submit
                                </small>
                                @error('location')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            {{-- <div class="form-group @error('picture') has-error @enderror">
                                <label for="picture">Foto <span class="text-danger">*</span></label>
                                <input id="picture" type="file" class="form-control-file" name="picture" accept="image/jpeg, image/png" capture="environment" required onchange="priviewImage()">
                                @error('picture')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div> --}}
                            <div class="form-group @error('picture') has-error @enderror">
                                <label for="picture">Ambil Foto <span class="text-danger">*</span></label>

                                <!-- Preview Kamera -->
                                <video id="camera" autoplay playsinline style="width: 100%; max-width: 300px;"></video>
                                <br>
                                <button type="button" id="take-photo" class="btn btn-sm btn-primary mt-2">Ambil Foto</button>

                                <!-- Preview Gambar -->
                                <canvas id="canvas" style="display: none;"></canvas>
                                <img id="img-preview" class="img-preview mt-2" width="30%" />

                                <!-- Input file tersembunyi untuk dikirim -->
                                <input type="file" id="picture" name="picture" style="display:none;" required>

                                @error('picture')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            <img class="img-preview" width="30%">
                            <div class="card-action">
                                <button type="submit" class="btn btn-success" id="btn-submit">Submit</button>
                                <a href="{{ route('absen.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        // Koordinat kantor
        const centerLat = Number('{{ Auth::user()->kantor->latitude }}');
        const centerLng = Number('{{ Auth::user()->kantor->longitude }}');
        // Radius maksimum dalam km
        const maxRadius = Number('{{ Auth::user()->kantor->jarak_maks }}'); // 20 meter = 0.02 km

        const kantor = $('#kantor').val();

        function priviewImage(params) {
            const image = $('#picture')[0];
            const imgPreview = $('.img-preview');

            imgPreview.css('display', 'block');
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.attr('src', oFREvent.target.result);
            }
        }

        $('#add-form').submit(function (e) {
            e.preventDefault();
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit').html('loading...');
            navigator.geolocation.getCurrentPosition((position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                console.log(`User coordinates: ${userLat}, ${userLng}`);
                const distance = getDistance(userLat, userLng, centerLat, centerLng);

                if (distance > maxRadius) {
                    $('#btn-submit').prop('disabled', false);
                    $('#btn-submit').html('Submit');
                    swal({
                        title: "Gagal!",
                        text: `Anda berada di luar radius ${maxRadius * 1000} meter dari kantor!`,
                        icon: "error",
                        button: {
                            text: "OK",
                            className: "btn btn-danger"
                        }
                    });
                } else {
                    getLocationName(userLat, userLng);
                }
            }, (error) => {
                console.error("Error mendapatkan lokasi:", error);
                swal({
                    title: "Gagal!",
                    text: "Gagal mendapatkan lokasi. Pastikan GPS Anda aktif dan izin lokasi diaktifkan.",
                    icon: "error",
                    button: {
                        text: "OK",
                        className: "btn btn-danger"
                    }
                });
            });
        })

        function getLocationName(lat, lng) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    $('#location').val(`${data.display_name}. (Lat:${data.lat}, lon:${data.lon})`);
                    $('#add-form')[0].submit();
                })
                .catch(error => {
                    console.error("Error:", error);
                    $('#btn-submit').prop('disabled', false);
                    $('#btn-submit').html('Submit');
                });
        }

        // Fungsi menghitung jarak antara dua koordinat (Haversine Formula)
        function getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Radius bumi dalam km
            const dLat = (lat2 - lat1) * (Math.PI / 180);
            const dLon = (lon2 - lon1) * (Math.PI / 180);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c; // Hasil dalam km
        }

        // Kamera dan capture
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const takePhotoBtn = document.getElementById('take-photo');
        const imgPreview = document.getElementById('img-preview');
        const pictureInput = document.getElementById('picture');

        // Minta akses kamera
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Gagal akses kamera:", err);
                alert("Gagal mengakses kamera. Pastikan izin sudah diberikan.");
            });

        takePhotoBtn.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Tampilkan preview
            const dataURL = canvas.toDataURL('image/jpeg');
            imgPreview.src = dataURL;
            imgPreview.style.display = 'block';

            // Convert dataURL ke file Blob, lalu ke File, lalu isi input file
            fetch(dataURL)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], `photo_${Date.now()}.jpg`, { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    pictureInput.files = dataTransfer.files;
                });
        });

    </script>
@endsection
