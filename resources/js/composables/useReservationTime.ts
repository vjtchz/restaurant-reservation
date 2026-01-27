// Time helpers shared by reservation-related views.
export const useReservationTime = () => {
  // Convert "HH:MM" to total minutes since midnight; returns null on invalid input.
  const toMinutes = (value: string) => {
    const [hours, minutes] = value.split(':').map((part) => Number(part));
    if (Number.isNaN(hours) || Number.isNaN(minutes)) {
      return null;
    }

    return hours * 60 + minutes;
  };

  // Convert total minutes since midnight back to zero-padded "HH:MM".
  const toTime = (totalMinutes: number) => {
    const hours = Math.floor(totalMinutes / 60)
      .toString()
      .padStart(2, '0');
    const minutes = (totalMinutes % 60).toString().padStart(2, '0');

    return `${hours}:${minutes}`;
  };

  // Add minutes to a "HH:MM" time, optionally clamping to a maximum "HH:MM".
  const addMinutes = (value: string, minutesToAdd: number, maxValue?: string) => {
    const startMinutes = toMinutes(value);
    if (startMinutes === null) {
      return value;
    }

    let targetMinutes = startMinutes + minutesToAdd;
    if (maxValue) {
      const maxMinutes = toMinutes(maxValue);
      if (maxMinutes !== null) {
        targetMinutes = Math.min(targetMinutes, maxMinutes);
      }
    }

    return toTime(targetMinutes);
  };

  // Build time slots between opening hours in minute steps for datalist usage.
  const buildTimeSlots = (from: string, to: string, stepMinutes: number) => {
    const startMinutes = toMinutes(from);
    const endMinutes = toMinutes(to);

    if (startMinutes === null || endMinutes === null || startMinutes >= endMinutes) {
      return [];
    }

    const slots: string[] = [];
    for (let totalMinutes = startMinutes; totalMinutes <= endMinutes; totalMinutes += stepMinutes) {
      slots.push(toTime(totalMinutes));
    }

    return slots;
  };

  return {
    toMinutes,
    toTime,
    addMinutes,
    buildTimeSlots,
  };
};
