@props([
    'courses' => [],
    'showMajor' => false,
    'caption' => null,
    'emptyTitle' => 'برای این پایه هنوز درسی ثبت نشده است.',
    'emptyDescription' => 'به‌محض ثبت برنامه درسی، فهرست دروس این پایه در همین بخش نمایش داده می‌شود.',
])

@php
    $items = collect($courses);
@endphp

@if ($items->isEmpty())
    <x-empty-state :title="$emptyTitle" :description="$emptyDescription" icon="graduation" compact />
@else
    {{-- One table, two layouts: a real table from `lg` upwards and a stack of
         cards below it. Nothing is duplicated in the DOM, so screen readers
         always meet a single, coherent list. --}}
    <div class="overflow-hidden rounded-xl border border-ink-900/10 bg-white dark:border-white/10 dark:bg-ink-950/40">
        <table class="w-full border-separate border-spacing-0 text-right">
            @if (filled($caption))
                <caption class="sr-only">{{ $caption }}</caption>
            @endif

            <thead class="hidden lg:table-header-group">
                <tr>
                    @if ($showMajor)
                        <th scope="col" class="w-44 border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                            رشته
                        </th>
                    @endif

                    <th scope="col" class="w-64 border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                        عنوان درس
                    </th>

                    <th scope="col" class="w-40 border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                        ساعات
                    </th>

                    <th scope="col" class="border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                        شرح
                    </th>
                </tr>
            </thead>

            <tbody class="block lg:table-row-group">
                @foreach ($items as $course)
                    <tr class="block border-b border-ink-900/8 last:border-b-0 lg:table-row lg:border-b lg:border-ink-900/8 dark:border-white/8">
                        @if ($showMajor)
                            <td class="hidden px-5 py-4 align-top lg:table-cell">
                                <span class="text-xs font-semibold leading-6 text-brand-600 dark:text-brand-300">{{ $course->major?->title }}</span>
                            </td>
                        @endif

                        <td class="block px-5 pt-5 align-top lg:table-cell lg:py-4">
                            <span class="block text-sm font-bold leading-7 text-ink-900 dark:text-white">{{ $course->title }}</span>

                            @if ($showMajor)
                                <span class="mt-1.5 block text-xs font-medium text-brand-600 dark:text-brand-300 lg:hidden">{{ $course->major?->title }}</span>
                            @endif
                        </td>

                        <td class="block px-5 pt-3 align-top lg:table-cell lg:py-4">
                            @if (filled($course->weekly_hours))
                                <span class="inline-flex items-center gap-1.5 rounded-lg border border-brand-200 bg-brand-50 px-2.5 py-1 text-[0.7rem] font-medium text-brand-700 dark:border-brand-500/25 dark:bg-brand-950/60 dark:text-brand-300">
                                    <x-pages.fa-number :value="$course->weekly_hours" />
                                    <span>ساعت در هفته</span>
                                </span>
                            @else
                                <span class="text-xs text-slate-400 dark:text-ink-500">—</span>
                            @endif
                        </td>

                        <td class="block px-5 pb-5 pt-3 align-top text-sm leading-8 text-slate-600 lg:table-cell lg:py-4 dark:text-ink-200">
                            {{ $course->description }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
