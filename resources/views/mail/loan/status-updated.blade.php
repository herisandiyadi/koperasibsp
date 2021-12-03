@component('mail::message')
# Pemberitahuan Pengajuan Pinjaman

Pengajuan Pinjamanmu dalam status {{$loanApplication->approval}}

@component('mail::button', ['url' => config('URL').'/loan-detail/'.encrypt($loanApplication->id)])
    Cek Sekarang
@endcomponent

Terima Kasih,<br>
{{ config('app.name') }}
@endcomponent
