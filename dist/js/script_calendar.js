Vue.component('calendar', {
  template: '#calendar-template',
  props: {
    value: {
      type: luxon.DateTime,
      required: true } },


  data() {
    // console.log(this.value);
    return {
      selected: this.value,
      days: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'] };

  },
  computed: {
    calendar() {
      let calendar = [];

      for (let week = 0; week < 6; week++) {
        let weekDays = [];
        for (let dayInWeek = 0; dayInWeek < 7; dayInWeek++) {
          weekDays.push(this.getDayInMonth(week, dayInWeek));
        }
        calendar.push(weekDays);
      }

      return calendar;
    } },

  methods: {
    getDayInMonth(week, dayInWeek) {
      return this.selected.startOf('month').startOf('week').plus({ week: week, day: dayInWeek });
    },
    select(date) {
      this.selected = date;
      this.$emit('input', date);
      $('#txt_fecha_exp').val(formatoDate(date))
      $('#modal_calendar').modal('hide')
      $('#txt_fecha_exp').focus();
      if($('#txt_primera_vez').val()==0 || $('#txt_primera_vez').val()=='')
      {        
        if($('#txt_TipoSubMod').val()=='R')
        {
            if($('#txt_paquetes').val()=='')
            {
              $('#modal_empaque').modal('show');
            }
        }
      }
    },
    classes(date) {
      return {
        selected: date.equals(this.selected),
        current: date.equals(luxon.DateTime.local().startOf('day')),
        inactive: date.month !== this.selected.month };

    } } });



new Vue({
  el: '#app',
  data: {
    date: luxon.DateTime.local() },

  computed: {
    dateString: {
      get() {
        return this.date.toISODate();
      },
      set(value) {
        this.date = luxon.DateTime.fromISO(value);
      } },

    prevMonth() {
      return this.date.minus({ month: 1 });
    },
    nextMonth() {
      return this.date.plus({ month: 1 });
    } } });