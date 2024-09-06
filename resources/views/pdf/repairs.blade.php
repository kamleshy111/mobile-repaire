<!DOCTYPE html>
<html>
<head>
    <title>Selected Repairs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Repairs Customer Details</h1>

    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Device Brand</th>
                <th>Device Model</th>
                <th>Issue</th>
                <th>Issue Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($repairs as $repair)
                <tr>
                    <td>{{ $repair->customer_name }}</td>
                    <td>{{ $repair->device_brand }}</td>
                    <td>{{ $repair->device_model }}</td>
                    <td>{{ $repair->issue }}</td>
                    <td>{{ $repair->issue_description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
