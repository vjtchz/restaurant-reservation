export function formatDateForInput(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

export function formatTimeForInput(date: Date): string {
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${hours}:${minutes}`;
}

export function addMinutesToTime(value: string, minutesToAdd: number): string {
    const [hours, minutes] = value.split(':').map(Number);
    const base = new Date();
    base.setHours(hours, minutes, 0, 0);
    base.setMinutes(base.getMinutes() + minutesToAdd);

    return formatTimeForInput(base);
}

export function formatReservationDate(value: string): string {
    const normalized = value.includes('T') ? value : value.replace(' ', 'T');
    const parsed = new Date(normalized);
    if (Number.isNaN(parsed.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat(undefined, { dateStyle: 'medium' }).format(parsed);
}

export function useReservationFormatting() {
    return {
        addMinutesToTime,
        formatDateForInput,
        formatReservationDate,
        formatTimeForInput,
    };
}
