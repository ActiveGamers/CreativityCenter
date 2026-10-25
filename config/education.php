<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Curriculum tables - one table per major
    |--------------------------------------------------------------------------
    |
    | The education page renders one table per major straight from this
    | file: editing the arrays below changes the tables, no database,
    | migration or seeder is involved. Every entry of "majors" is keyed on
    | the slug of the major and carries its own metadata plus its courses;
    | a course belongs to one grade level ('tenth', 'eleventh',
    | 'twelfth') and the rows are grouped by grade inside the table.
    |
    | The shape is the contract the future admin panel will write to, so
    | keep it stable: 'title', 'subtitle', 'icon', 'accent', 'summary'
    | and 'courses' (a list of 'grade', 'title', 'weekly_hours' and
    | 'description'). The icon names are the ones <x-icon /> ships
    | ('network', 'mechatronics', 'chip', ...) and the accent values are
    | 'brand', 'accent', 'ink' and 'success'.
    |
    | A major without courses renders no table, and an empty file leaves
    | the page with its empty state.
    |
    */

    'majors' => [

        'network-and-software' => [
            'title' => 'شبکه و نرم‌افزار رایانه',
            'subtitle' => 'دانش بنیان در حوزه فناوری اطلاعات',
            'icon' => 'network',
            'accent' => 'brand',
            'summary' => 'طراحی، پیاده‌سازی و مدیریت شبکه‌های رایانه‌ای به همراه توسعه نرم‌افزارهای تحت وب.',
            'courses' => [
                ['grade' => 'tenth', 'title' => 'مبانی شبکه و سخت‌افزار', 'weekly_hours' => 3, 'description' => 'شناخت اجزای رایانه، سخت‌افزار، انواع شبکه و اصول اولیه ارتباط در شبکه.'],
                ['grade' => 'tenth', 'title' => 'تفکر الگوریتمی و برنامه‌نویسی مقدماتی', 'weekly_hours' => 3, 'description' => 'حل مسئله با الگوریتم و آشنایی با مبانی زبان‌های برنامه‌نویسی.'],
                ['grade' => 'tenth', 'title' => 'برنامه‌نویسی وب', 'weekly_hours' => 3, 'description' => 'طراحی صفحات وب با زبان‌های نشانه‌گذاری و کار با پایگاه داده ساده.'],
                ['grade' => 'tenth', 'title' => 'ریاضیات فنی و کاربرد آن در رایانه', 'weekly_hours' => 3, 'description' => 'کاربرد مفاهیم ریاضی در حل مسائل فنی رشته رایانه.'],
                ['grade' => 'eleventh', 'title' => 'طراحی و پیاده‌سازی شبکه', 'weekly_hours' => 4, 'description' => 'طراحی توپولوژی، پیکربندی تجهیزات شبکه و عیب‌یابی اتصال.'],
                ['grade' => 'eleventh', 'title' => 'برنامه‌نویسی وب پیشرفته', 'weekly_hours' => 3, 'description' => 'توسعه وب‌سایت‌های پویا با پایگاه داده و معماری سه‌لایه.'],
                ['grade' => 'eleventh', 'title' => 'امنیت شبکه', 'weekly_hours' => 3, 'description' => 'آشنایی با تهدیدهای رایانه‌ای و روش‌های مقابله با آن‌ها.'],
                ['grade' => 'eleventh', 'title' => 'پایگاه داده و مدیریت داده', 'weekly_hours' => 3, 'description' => 'طراحی پایگاه داده رابطه‌ای و نوشتن پرس‌وجوهای بهینه.'],
                ['grade' => 'eleventh', 'title' => 'سیستم‌عامل سرور و سرویس‌های شبکه', 'weekly_hours' => 3, 'description' => 'نصب، پیکربندی و مدیریت سرویس‌های شبکه روی سرور.'],
                ['grade' => 'twelfth', 'title' => 'مدیریت شبکه و سیستم‌عامل سرور', 'weekly_hours' => 4, 'description' => 'مدیریت منابع، کاربران و سیاست‌های دسترسی در شبکه سازمانی.'],
                ['grade' => 'twelfth', 'title' => 'امنیت و پایش شبکه', 'weekly_hours' => 3, 'description' => 'پایش ترافیک، ثبت رخدادها و مقابله با نفوذ در شبکه.'],
                ['grade' => 'twelfth', 'title' => 'پیاده‌سازی سرویس‌های سازمانی', 'weekly_hours' => 3, 'description' => 'راه‌اندازی سرویس‌های اشتراکی و ابری در محیط واقعی کار.'],
                ['grade' => 'twelfth', 'title' => 'کارگاه پروژه پایانی نرم‌افزار و شبکه', 'weekly_hours' => 4, 'description' => 'طراحی و ارائه یک پروژه کامل شبکه و نرم‌افزار از ابتدا تا انتها.'],
            ],
        ],

        'mechatronics' => [
            'title' => 'مکاترونیک',
            'subtitle' => 'تلاقی مکانیک، الکترونیک و کنترل',
            'icon' => 'mechatronics',
            'accent' => 'accent',
            'summary' => 'طراحی و کنترل سامانه‌های صنعتی هوشمند با تکیه بر الکترونیک، کنترل و اتوماسیون.',
            'courses' => [
                ['grade' => 'tenth', 'title' => 'مبانی مکاترونیک', 'weekly_hours' => 3, 'description' => 'شناخت اجزای سامانه‌های مکاترونیکی و ارتباط میان مکانیک، الکترونیک و کنترل.'],
                ['grade' => 'tenth', 'title' => 'مدار الکتریکی و اندازه‌گیری', 'weekly_hours' => 4, 'description' => 'کار با مدارهای الکتریکی، اندازه‌گیری جریان و ولتاژ و استفاده از ابزار دقیق.'],
                ['grade' => 'tenth', 'title' => 'مبانی برنامه‌نویسی و کنترل', 'weekly_hours' => 3, 'description' => 'نوشتن برنامه‌های ساده برای کنترل فرایندهای صنعتی.'],
                ['grade' => 'tenth', 'title' => 'ریاضیات فنی و کاربرد آن در مکاترونیک', 'weekly_hours' => 3, 'description' => 'کاربرد مفاهیم ریاضی در تحلیل مدار و سامانه‌های کنترلی.'],
                ['grade' => 'eleventh', 'title' => 'کنترل صنعتی و PLC', 'weekly_hours' => 4, 'description' => 'برنامه‌نویسی کنترل‌کننده‌های منطقی و پیاده‌سازی فرایندهای صنعتی.'],
                ['grade' => 'eleventh', 'title' => 'سیستم‌های مکاترونیکی', 'weekly_hours' => 3, 'description' => 'طراحی و ساخت سامانه‌های مکاترونیکی و تحلیل عملکرد آن‌ها.'],
                ['grade' => 'eleventh', 'title' => 'مدارهای آنالوگ و دیجیتال', 'weekly_hours' => 3, 'description' => 'تحلیل و طراحی مدارهای آنالوگ و دیجیتال در تجهیزات صنعتی.'],
                ['grade' => 'eleventh', 'title' => 'رباتیک و اتوماسیون', 'weekly_hours' => 3, 'description' => 'آشنایی با بازوهای رباتیک و کاربرد اتوماسیون در خط تولید.'],
                ['grade' => 'eleventh', 'title' => 'اندازه‌گیری و ابزار دقیق', 'weekly_hours' => 3, 'description' => 'کاربرد حسگرها و ابزارهای اندازه‌گیری دقیق در فرایندهای صنعتی.'],
                ['grade' => 'twelfth', 'title' => 'طراحی و ساخت سیستم‌های مکاترونیکی', 'weekly_hours' => 4, 'description' => 'طراحی کامل یک سامانه مکاترونیکی از تحلیل تا ساخت و راه‌اندازی.'],
                ['grade' => 'twelfth', 'title' => 'کنترل پیشرفته و اتوماسیون صنعتی', 'weekly_hours' => 3, 'description' => 'به‌کارگیری روش‌های پیشرفته کنترل برای خودکارسازی فرایندها.'],
                ['grade' => 'twelfth', 'title' => 'رباتیک و اتوماسیون پیشرفته', 'weekly_hours' => 3, 'description' => 'پیاده‌سازی پروژه‌های رباتیک و سامانه‌های خودکار در مقیاس صنعتی.'],
                ['grade' => 'twelfth', 'title' => 'کارگاه پروژه پایانی مکاترونیک', 'weekly_hours' => 4, 'description' => 'ساخت و ارائه یک پروژه عملی مکاترونیکی همراه با مستندات فنی.'],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Grade guidance
    |--------------------------------------------------------------------------
    |
    | The cards underneath the tables: what each grade level adds to the
    | path of a student. Icons follow the same names as above.
    |
    */

    'guidance' => [
        ['icon' => 'graduation', 'title' => 'پایهٔ دهم', 'text' => 'دورهٔ مشترک آشنایی با شبکه، سخت‌افزار، مدارهای الکتریکی و مبانی برنامه‌نویسی؛ نقطهٔ شروع کارگاه‌های تخصصی.'],
        ['icon' => 'chart', 'title' => 'پایهٔ یازدهم', 'text' => 'ورود به دروس تخصصی: طراحی و پیکربندی شبکه، امنیت، کنترل صنعتی، PLC و رباتیک.'],
        ['icon' => 'trophy', 'title' => 'پایهٔ دوازدهم', 'text' => 'پروژه‌های عملی و کارگاه پایانی؛ از مدیریت سرور و سرویس‌های سازمانی تا ساخت کامل یک سامانهٔ مکاترونیکی.'],
    ],

];
