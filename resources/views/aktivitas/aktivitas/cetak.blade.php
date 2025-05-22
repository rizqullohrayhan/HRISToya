<!DOCTYPE html>
<html>

<head>
    <title>Buku Aktivitas</title>
    <style>
        @page {
            margin: 0px;
            font-family: Arial, Helvetica, sans-serif;
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0px;
            padding: 0px;
            font-family: Arial, Helvetica, sans-serif;
        }

        small {
            font-size: 12px;
            color: #888;
        }

        .ajax-page-load-indicator {
            display: none;
            visibility: hidden;
        }

        #report-header {
            position: relative;
            border-top: 2px solid #0066cc;
            border-bottom: 5px solid #0066cc;
            background: #fafafa;
            padding: 10px;
        }

        #report-header table {
            margin: 0;
        }

        #report-header .sub-title {
            font-size: small;
            color: #888;
        }

        #report-header img {
            height: 50px;
            width: 200px;
        }

        #report-title,
        #report-periode,
        #report-user {
            background: #fafafa;
            margin-top: 0px;
            margin-bottom: 0px;
            padding: 0px 20px;
            font-size: 17px;
            text-align: left;
        }

        #report-body {
            padding: 20px;
            font-family: Arial;
            font-size: 11px;
        }

        #report-footer {
            padding: 10px;
            background: #fafafa;
            border-top: 2px solid #0066cc;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 98%;
            overflow: hidden;
            margin: 0 auto;
        }

        #report-footer table {
            margin: 0;
            overflow: hidden;
        }

        table,
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #eceeef;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #eceeef;
        }

        .table tbody+tbody {
            border-top: 2px solid #eceeef;
        }

        .table .table {
            background-color: #fff;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #eceeef;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #eceeef;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-active,
        .table-active>th,
        .table-active>td {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover>td,
        .table-hover .table-active:hover>th {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-success,
        .table-success>th,
        .table-success>td {
            background-color: #dff0d8;
        }

        .table-hover .table-success:hover {
            background-color: #d0e9c6;
        }

        .table-hover .table-success:hover>td,
        .table-hover .table-success:hover>th {
            background-color: #d0e9c6;
        }

        .table-info,
        .table-info>th,
        .table-info>td {
            background-color: #d9edf7;
        }

        .table-hover .table-info:hover {
            background-color: #c4e3f3;
        }

        .table-hover .table-info:hover>td,
        .table-hover .table-info:hover>th {
            background-color: #c4e3f3;
        }

        .table-warning,
        .table-warning>th,
        .table-warning>td {
            background-color: #fcf8e3;
        }

        .table-hover .table-warning:hover {
            background-color: #faf2cc;
        }

        .table-hover .table-warning:hover>td,
        .table-hover .table-warning:hover>th {
            background-color: #faf2cc;
        }

        .table-danger,
        .table-danger>th,
        .table-danger>td {
            background-color: #f2dede;
        }

        .table-hover .table-danger:hover {
            background-color: #ebcccc;
        }

        .table-hover .table-danger:hover>td,
        .table-hover .table-danger:hover>th {
            background-color: #ebcccc;
        }

        .thead-inverse th {
            color: #fff;
            background-color: #292b2c;
        }

        .thead-default th {
            color: #464a4c;
            background-color: #eceeef;
        }

        .table-inverse {
            color: #fff;
            background-color: #292b2c;
        }

        .table-inverse th,
        .table-inverse td,
        .table-inverse thead th {
            border-color: #fff;
        }

        .table-inverse.table-bordered {
            border: 0;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive.table-bordered {
            border: 0;
        }
    </style>
</head>

<body>
    <div id="report-header">
        <table class="table table-sm">
            <tr>
                <th>
                    <div id="report-title">Buku Aktivitas</div>
                    <div id="report-periode">Periode :
                        {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }} s/d
                        {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
                    </div>
                    <div id="report-periode">Nama : {{ $user->name }}</div>
                </th>
                <th>
                    <img width="50" height="50" src="{{ asset('logo/toyalogolengkap.jpg') }}">
                </th>
            </tr>
        </table>
    </div>
    <div id="report-body">
        <table class="table  table-striped table-sm text-left">
            <thead class="table-header bg-light">
                <tr>
                    <th class="td-sno">#</th>
                    <th class="td-tgl"> Tgl</th>
                    <th class="td-jam_awal"> Jam Awal</th>
                    <th class="td-jam_akhir"> Jam Akhir</th>
                    <th class="td-rencana"> Rencana</th>
                    <th class="td-aktifitas"> Aktifitas</th>
                    <th class="td-hasil"> Hasil</th>
                    <th class="td-file"> File</th>
                </tr>
            </thead>
            <tbody class="page-data" id="page-data-list-page-0uq2amwd8r36">
                <!--record-->
                @foreach ($aktivitas as $aktivita)
                <tr>
                    <th class="td-sno">{{ $loop->iteration }}</th>
                    <td class="td-tgl">
                        <span class="is-editable">
                            {{ \Carbon\Carbon::parse($aktivita->tanggal)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="td-jam_awal">
                        <span class="is-editable">
                            {{ $aktivita->jam_awal }}
                        </span>
                    </td>
                    <td class="td-jam_akhir">
                        <span class="is-editable">
                            {{ $aktivita->jam_akhir }}
                        </span>
                    </td>
                    <td class="td-rencana">
                        <span class="is-editable">
                            {{ $aktivita->rencana }}
                        </span>
                    </td>
                    <td class="td-aktifitas">
                        <span class="is-editable">
                            {{ $aktivita->aktivitas }}
                        </span>
                    </td>
                    <td class="td-hasil">
                        <span class="is-editable">
                            {{ $aktivita->hasil }}
                        </span>
                    </td>
                    <td class="td-file">{{ $aktivita->file }}</td>
                </tr>
                @endforeach
                <!--endrecord-->
            </tbody>
        </table>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
