@php
    $qrService = app(\App\Services\QrCodeService::class);
    $doorUrl = route('rooms.show', $record->slug);
    $qrSvg = $qrService->generateSvg($doorUrl, 200);
@endphp

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1rem 0; width: 100%;">
    <div id="door-sticker-card-{{ $record->id }}" style="background-color: #ffffff !important; color: #1c1917 !important; width: 100%; max-width: 22rem; padding: 2rem 1.75rem; border-radius: 1.5rem; border: 2.5px solid #292524; box-shadow: 0 15px 30px -5px rgba(0,0,0,0.25); display: flex; flex-direction: column; align-items: center; text-align: center; box-sizing: border-box;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 0.75rem;">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo SIMRU" style="width: 2.25rem; height: 2.25rem; max-width: 2.25rem; max-height: 2.25rem; object-fit: contain; display: block; border-radius: 0.5rem;" />
            <div style="text-align: left; line-height: 1.15;">
                <span style="font-size: 0.625rem; font-weight: 800; letter-spacing: 0.08em; color: #A1553E; display: block; text-transform: uppercase;">
                    FAKULTAS TEKNIK
                </span>
                <span style="font-size: 0.95rem; font-weight: 900; color: #1c1917; display: block;">
                    SIMRU TEKNIK UMPO
                </span>
            </div>
        </div>

        <div style="width: 100%; height: 2px; background-color: #292524; margin-bottom: 1rem;"></div>

        <h3 style="font-size: 1.15rem; font-weight: 900; color: #1c1917; margin: 0 0 0.25rem 0; line-height: 1.25;">
            {{ $record->name }}
        </h3>
        <p style="font-size: 0.8rem; font-weight: 700; color: #57534e; margin: 0 0 1rem 0;">
            {{ $record->code }} &bull; {{ $record->location }}
        </p>

        <div style="background-color: #ffffff; padding: 0.75rem; border-radius: 1.25rem; border: 1.5px solid #e7e5e4; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; width: 200px; height: 200px; box-sizing: content-box;">
            <div style="width: 200px; height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                {!! $qrSvg !!}
            </div>
        </div>

        <div style="background-color: #f5f5f4; border-radius: 0.625rem; padding: 0.4rem 0.85rem; margin-bottom: 0.75rem; width: 100%; box-sizing: border-box;">
            <span style="font-family: monospace; font-size: 0.75rem; font-weight: 800; color: #292524; letter-spacing: 0.02em;">
                ID: {{ $record->qr_identifier }}
            </span>
        </div>

        <p style="font-size: 0.68rem; color: #78716c; line-height: 1.35; margin: 0;">
            Arahkan kamera smartphone ke kode QR di atas untuk memeriksa jadwal ketersediaan atau mengajukan peminjaman ruangan.
        </p>
    </div>

    <div style="margin-top: 1.5rem; display: flex; justify-content: center; width: 100%;">
        <button
            type="button"
            onclick="printSticker_{{ $record->id }}()"
            style="background: linear-gradient(180deg, #d97706 0%, #b45309 100%); color: #ffffff; font-size: 0.825rem; font-weight: 800; padding: 0.7rem 1.5rem; border-radius: 0.85rem; border: 1px solid rgba(255,255,255,0.2); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 8px 16px -2px rgba(180,83,9,0.4); text-transform: uppercase; letter-spacing: 0.03em;"
        >
            <svg style="width: 1.125rem; height: 1.125rem; min-width: 1.125rem; min-height: 1.125rem; max-width: 1.125rem; max-height: 1.125rem; display: inline-block; vertical-align: middle;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span>Cetak Stiker Pintu Ini</span>
        </button>
    </div>
</div>

<script>
function printSticker_{{ $record->id }}() {
    const cardHtml = document.getElementById('door-sticker-card-{{ $record->id }}').outerHTML;
    const printWindow = window.open('', '_blank', 'width=650,height=750');
    printWindow.document.write('<!DOCTYPE html><html><head><title>Cetak Stiker - {{ $record->code }}</title><style>body { margin: 0; padding: 2rem; display: flex; justify-content: center; align-items: center; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #fff; } @page { size: auto; margin: 10mm; }</style></head><body>' + cardHtml + '</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 400);
}
</script>
