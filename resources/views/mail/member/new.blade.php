@component('mail::message')
# Pemberitahuan

Anggota baru telah terdaftar.

Nama: {{$member->full_name}}

NIK: {{$member->nik_bsp}}

@component('mail::button', ['url' => config('URL').'/profile-member/'.encrypt($member->id)])
Cek Sekarang
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
