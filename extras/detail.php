<?php include './conf.php'; ?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="./icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./icon/favicon-16x16.png">
    <link rel="manifest" href="./icon/site.webmanifest">
</head>

<body class="p-5 bg-gray-700 w-full h-full flex flex-col text-gray-200 antialiased">
    <div class="flex flex-row mb-10 gap-8">
        <div class="flex flex-row gap-5 align-middle">
            <button id="back" onclick="history.back()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
            </button>
            <h1 class="text-3xl font-bold">
                Detail
            </h1>
        </div>
        <div>

        </div>

    </div>

    <div class="flex flex-row w-full h-full justify-center">
        <!-- <div class="flex flex-col basis-3/12">
            <label for="datepicker" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Date:</label>
            <div>
                <input type="date" id="start" name="start" class="mt-1 p-2 border rounded-md focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
                <input type="date" id="end" name="end" class="mt-1 p-2 border rounded-md focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300">
            </div>
        </div> -->
        <div class="flex flex-col basis-9/12">
            <div class="overflow-x-auto">
                <table class="table table-dark bg-gray-800 rounded-lg shadow-md w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-white">Id Transaksi</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Nilai</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Deposit</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Jurnal</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Jurnal Detail</th>
                            <!-- <th class="px-4 py-3 text-left font-bold text-white">Action</th> -->
                        </tr>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(() => {
            $.ajax({
                url: '<?php echo BASE_URL . 'extras/controller.php?method=detail'; ?>',
                method: "GET",
                dataType: "json",
                success: (r) => {
                    if (r.length) {
                        r.map((d, i) => {
                            $("#tbody").append(
                                `
                                <tr class="border-b border-gray-600">
                                    <td class="px-4 py-3 text-white">${d.id_trans}</td>
                                    <td class="px-4 py-3 text-white">${d.value}</td>
                                    <td class="px-4 py-3 text-white">${d.deposit}</td>
                                    <td class="px-4 py-3 text-white">${d.total_debet}</td>
                                    <td class="px-4 py-3 text-white">${d.debet}</td>
                                </tr>
                                `
                            )
                        })
                    }
                },
                error: (e, s, t) => {
                    console.log(s, t);
                }
            })
        })
    </script>
</body>

</html>