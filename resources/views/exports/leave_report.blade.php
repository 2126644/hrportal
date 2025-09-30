<table>
    <thead>
        <tr>
            <th>Leave Type</th>
            <th>Entitlement</th>
            @for ($m = 1; $m <= 12; $m++)
                <th>{{ \Carbon\Carbon::create()->month($m)->shortMonthName }}</th>
            @endfor
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leaveTypes as $type)
            @php $rowTotal = 0; @endphp
            <tr>
                <td>{{ $type }}</td>
                <td>0</td> {{-- Replace 0 with actual entitlement if available --}}
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $count = $reportData[$type][$m] ?? 0;
                        $rowTotal += $count;
                    @endphp
                    <td>{{ $count }}</td>
                @endfor
                <td>{{ $rowTotal }}</td>
            </tr>
        @endforeach
    </tbody>
</table>