
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-READY - Certificate of Completion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Poppins:wght@400;600&display=swap');
        .certificate-font { font-family: 'Cinzel', serif; }
        .body-font { font-family: 'Poppins', sans-serif; }
        
        /* Para maging mukhang papel kapag pi-nprint o tiningnan */
        @media print {
            body { background: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-slate-200 body-font flex items-center justify-center min-h-screen m-0">

    <div class="absolute top-5 right-5 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-semibold hover:bg-blue-700">
            🖨️ I-print / I-save bilang PDF
        </button>
    </div>

    <!-- Certificate Box -->
    <div class="w-[900px] h-[630px] bg-white border-[16px] border-double border-amber-700 p-10 text-center relative shadow-2xl flex flex-col justify-between">
        
        <!-- Header -->
        <div>
            <h3 class="text-gray-500 tracking-widest uppercase text-sm font-semibold">B-READY Disaster Preparedness System</h3>
            <h1 class="certificate-font text-4xl font-bold text-amber-800 mt-3">Certificate of Completion</h1>
            <p class="text-gray-600 italic text-sm mt-1">Ipinagkaloob ito kay</p>
        </div>

        <!-- Recipient Name -->
        <div class="my-auto">
            <h2 class="certificate-font text-3xl font-bold text-gray-800 border-b-2 border-gray-400 inline-block px-10 pb-2">
                Pangalan ng Estudyante / Trainer
            </h2>
            <p class="text-gray-600 text-sm mt-4">Para sa matagumpay na pagtatapos ng kursong pagsasanay at pagsusulit sa</p>
            <h4 class="text-lg font-bold text-blue-900 mt-1">Disaster Preparedness Quiz</h4>
        </div>

        <!-- Footer / Details -->
        <div class="flex justify-between items-end border-t border-gray-300 pt-4">
            <div class="text-left">
                <p class="text-xs text-gray-500">Certificate Code:</p>
                <p class="font-bold text-green-700 text-sm">{{ $certificate->certificate_code ?? 'BREADY-TEST-12345' }}</p>
            </div>
            <div>
                <div class="border-b border-gray-800 w-48 mb-1"></div>
                <p class="text-xs text-gray-600 font-semibold">DRR Trainer / Administrator</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Petsa ng Pagkaloob:</p>
                <p class="font-bold text-gray-700 text-sm">{{ $certificate->issued_at ?? now()->toDateString() }}</p>
            </div>
        </div>

    </div>

</body>
</html>