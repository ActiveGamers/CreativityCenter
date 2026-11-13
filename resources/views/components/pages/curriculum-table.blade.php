@props([
    'courses' => [],
    'showMajor' => false,
    'majors' => [],
    'caption' => null,
    'emptyTitle' => 'برای این پایه هنوز درسی ثبت نشده است.',
    'emptyDescription' => 'به‌محض ثبت برنامه درسی، فهرست دروس این پایه در همین بخش نمایش داده می‌شود.',
])

@php
    $items = collect($courses);

    /* The icon and the accent of a major are not part of the eager loaded
       relation, so the page hands the list of majors over for the group
       headers. */
    $majorMeta = collect($majors)->keyBy(fn ($major) => data_get($major, 'slug'));

    /* In `showMajor` mode the rows are grouped by major: the name of the
       major appears once per group instead of once per row. */
    $groups = $showMajor
        ? $items->groupBy(fn ($course) => (int) (data_get($course, 'major')?->id ?? 0))
        : collect([0 => $items]);

    $groupTones = [
        'brand' => 'bg-brand-50 text-brand-600 dark:bg-brand-950/60 dark:text-brand-300',
        'accent' => 'bg-accent-50 text-accent-600 dark:bg-accent-950/60 dark:text-accent-300',
        'ink' => 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-100',
        'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-300',
    ];
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
                    <th scope="col" class="w-64 border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                        عنوان درس
                    </th>

                    <th scope="col" class="w-32 border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                        ساعت در هفته
                    </th>

                    <th scope="col" class="border-b border-ink-900/10 bg-ink-50/80 px-5 py-3.5 text-xs font-semibold text-ink-700 dark:border-white/10 dark:bg-white/5 dark:text-ink-100">
                        شرح
                    </th>
                </tr>
            </thead>

            <tbody class="block lg:table-row-group">
                @foreach ($groups as $group)
                    @if ($showMajor)
                        @php
                            $groupMajor = $group->first()?->major;
                            $groupMeta = $majorMeta->get(data_get($groupMajor, 'slug'));
                            $groupTone = $groupTones[data_get($groupMeta, 'accent', 'brand')] ?? $groupTones['brand'];
                        @endphp

                        {{-- The group header: the name of the major, once for
                             the whole group of courses. --}}
                        <tr class="block lg:table-row">
                            <th colspan="3" scope="rowgroup" class="block border-b border-ink-900/8 bg-ink-50/80 px-5 py-3 text-start align-middle lg:table-cell dark:border-white/8 dark:bg-white/5">
                                <span class="flex items-center gap-2.5">
                                    <span class="grid size-8 shrink-0 place-items-center rounded-lg {{ $groupTone }}">
                                        <x-icon :name="data_get($groupMeta, 'icon', 'chip')" class="size-4" />
                                    </span>

                                    <span class="text-sm font-bold leading-6 text-ink-900 dark:text-white">{{ data_get($groupMajor, 'title') }}</span>

                                    <span class="ms-auto text-xs tabular-nums text-slate-500 dark:text-ink-300">
                                        <x-pages.fa-number :value="$group->count()" /> درس
                                    </span>
                                </span>
                            </th>
                        </tr>
                    @endif

                    @foreach ($group as $course)
                        <tr class="block border-b border-ink-900/8 last:border-b-0 lg:table-row lg:border-b lg:border-ink-900/8 dark:border-white/8">
                            <td class="block px-5 pt-4 align-top lg:table-cell lg:py-4">
                                <span class="block text-sm font-bold leading-7 text-ink-900 dark:text-white">{{ $course->title }}</span>
                            </td>

                            <td class="block px-5 pt-2 align-top lg:table-cell lg:py-4">
                                @if (filled($course->weekly_hours))
                                    <span class="text-sm font-bold tabular-nums text-brand-600 dark:text-brand-300">
                                        <x-pages.fa-number :value="$course->weekly_hours" />
                                    </span>
                                    <span class="ms-1 text-xs text-slate-500 lg:hidden dark:text-ink-300">ساعت</span>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-ink-500">—</span>
                                @endif
                            </td>

                            <td class="block px-5 pb-5 pt-2 align-top text-sm leading-8 text-slate-600 lg:table-cell lg:py-4 dark:text-ink-200">
                                {{ $course->description }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endif
