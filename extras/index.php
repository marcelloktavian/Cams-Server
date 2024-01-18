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
        <h1 class="text-3xl font-bold">
            Checker Module
        </h1>
        <div>

        </div>

    </div>

    <div class="flex flex-row w-full h-full justify-center">
        <div class="flex flex-col basis-10/12">
            <div class="overflow-x-auto">
                <table class="table table-dark bg-gray-800 rounded-lg shadow-md w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-white">No</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Event</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Status</th>
                            <th class="px-4 py-3 text-left font-bold text-white">last runtime</th>
                            <th class="px-4 py-3 text-left font-bold text-white">count</th>
                            <th class="px-4 py-3 text-left font-bold text-white">Action</th>
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
                url: '<?php echo BASE_URL . 'extras/controller.php?method=index'; ?>',
                method: "GET",
                dataType: "json",
                success: (r) => {
                    r.map((d, i) => {
                        $("#tbody").append(
                            `
                            <tr class="border-b border-gray-600">
                                <td class="px-4 py-3 text-white">${i + 1}</td>
                                <td class="px-4 py-3 text-white">${d.event}</td>
                                <td class="px-4 py-3 text-white">${d.status}</td>
                                <td class="px-4 py-3 text-white">${d.last}</td>
                                <td class="px-4 py-3 text-white">${d.jumlah_baris}</td>
                                <td class="px-4 py-3 text-white"><a href="<?= BASE_URL . 'extras/detail.php' ?>" id="btn" class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600">Lihat</a></td>
                            </tr>
                            `
                        )
                    })
                },
                error: (e, s, t) => {
                    console.log(s, t);
                }
            })

            $("#btn").click(() => {
                // window.location.href = "<?= BASE_URL . 'extras/detail.php' ?>";
                console.log("asasas");
            })
        })
    </script>
</body>

</html>