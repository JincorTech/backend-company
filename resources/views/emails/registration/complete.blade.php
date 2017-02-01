@extends('emails.registration.abstract')

@section('content')
    <tr>
        <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                <tr>
                    <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                        <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">
                            {{ trans('mails.registration_complete.greeting', ['employee_name' => $employeeName]) }}
                        </p>
                        <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">
                            {{ trans('mails.registration_complete.intro', ['employee_position' => $employeePosition,'company_name' => $companyName]) }}
                        </p>
                        <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">
                            {{  trans('mails.registration_complete.outro') }}
                        </p>
                        <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">
                            {{  trans('mails.registration_complete.bye') }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection