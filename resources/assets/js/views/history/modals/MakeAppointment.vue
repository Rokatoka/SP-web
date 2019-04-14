<template>
    <b-modal  ref="modalfirst" size="lg" title="Make Appointment" hide-footer="true">
        <div class="form-group row">
            <label class="col-3 col-form-label">Choose Hospital</label>
            <div class="col-9">
                <select class="form-control" v-model="hospital" @change="selectDep">
                    <option v-for="hosp in $common.data.hospitals" :value="hosp.id">{{hosp.name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Choose Department</label>
            <div class="col-9">
                <select class="form-control" v-model="department" @change="selectType">
                    <option v-for="dep in departments" :value="dep.id">{{dep.name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Choose Doctor Type</label>
            <div class="col-9">
                <select class="form-control" v-model="position" @change="selectDoctor">
                    <option v-for="pos in positions" :value="pos.id">{{pos.name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Choose Doctor</label>
            <div class="col-9">
                <select class="form-control" v-model="doctor" @change="selectSchedule">
                    <option v-for="doc in doctors" :value="doc.id">{{doc.last_name}} {{doc.first_name}} {{doc.patronymic}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row" v-if="doctor != ''">
            <label class="col-3 col-form-label">Choose Date</label>
            <div class="col-9">
                <datepicker lang="en" v-model="date"  :disabledDates="disabledDates"
                            bootstrap-styling=true :value="String"
                :format="customFormatter(date)"></datepicker>
            </div>
        </div>
        <div class="form-group row" v-if="date != ''">
            <label class="col-3 col-form-label">Choose Time</label>
            <div class="col-9">
                <date-picker value-type="format" v-model="time" lang="en" type="time" format="HH:mm:ss"
                              :time-picker-options="optionsTime"></date-picker>
            </div>
        </div>
        <div class="help-block" v-if="error">{{ error }}</div>
        <button
                type="button"
                class="btn btn-primary"
                @click="send"
        :disabled="time == ''">
            <i v-show="dataLoaded" class="fa fa-spinner fa-spin"></i> Confirm
        </button>
    </b-modal>
</template>

<script>

    import { post } from '../../../helpers/api'
    import DatePicker from 'vuejs-datepicker';
    import moment from 'moment'

    export default {

        props:['user'],

        data(){
            return {
                dataLoaded: false,
                hospital:'',
                departments:[],
                department:'',
                positions:[],
                position:'',
                doctors:[],
                doctor:'',
                date:'',
                disabledDates:{
                    to: new Date(Date.now() - 8640000),
                    days: []
                },
                schedule:'',
                dateToSend:'',
                time:'',
                optionsTime:{
                    start:'',
                    step: '' ,
                    end: ''
                },
                appointments:'',
                error:''
            }
        },

        components:{
            'datepicker': DatePicker
        },

        methods: {
            showModal() {
                this.$refs.modalfirst.show();
                console.log(this.user);
            },
            hideModal() {
                this.$refs.modalfirst.hide();
            },

            selectDep(){
                let _this = this;
                post(
                    _this,
                    '/api/appointment/department-get',
                    {
                        hospital: _this.hospital
                    },
                    function(response){
                        _this.departments = response.data;
                    },
                    function (error) {

                    }
                );
            },
            selectType(){
                let _this = this;
                post(
                    _this,
                    '/api/appointment/doctor-type-get',
                    {
                        department: _this.department
                    },
                    function(response){
                        _this.positions = response.data;
                    },
                    function (error) {

                    }
                );
            },
            selectDoctor(){
                let _this = this;
                post(
                    _this,
                    '/api/appointment/doctor-get',
                    {
                        position: _this.position,
                        department: _this.department
                    },
                    function(response){
                        _this.doctors = response.data;
                    },
                    function (error) {

                    }
                );
            },
            selectSchedule(){
                let _this = this;

                post(
                    _this,
                    '/api/appointment/schedule',
                    {
                        doctor: _this.doctor
                    },
                    function(response){
                        _this.schedule = response.data.schedule;
                        _this.appointments = response.data.appointments;
                        console.log(response.data);
                        if(_this.schedule.data[0].Monday == ""){
                            _this.disabledDates.days.push(1)
                        }
                        if(_this.schedule.data[0].Tuesday == ""){
                            _this.disabledDates.days.push(2)
                        }
                        if(_this.schedule.data[0].Wednesday == ""){
                            _this.disabledDates.days.push(3)
                        }
                        if(_this.schedule.data[0].Thursday == ""){
                            _this.disabledDates.days.push(4)
                        }
                        if(_this.schedule.data[0].Friday == ""){
                            _this.disabledDates.days.push(5)
                        }
                        _this.disabledDates.days.push(6, 0)
                    },
                    function (error) {

                    }
                );

            },

            customFormatter(date) {

                return moment(date).format('MMMM Do YYYY');
            },

            send(){
                let _this = this;
                _this.dateToSend = moment(_this.date).format('YYYY-MM-DD');

                post(
                    _this,
                    '/api/appointment/send',
                    {
                        patient: _this.user,
                        doctor: _this.doctor,
                        department: _this.department,
                        date: _this.dateToSend,
                        time: _this.time
                    },
                    function(response){
                        _this.$emit('upd');
                        _this.hideModal();
                    },
                    function (error) {

                    }
                );
            }
        },

        watch:{
            date: function(){
                let dt = moment(this.date, "YYYY-MM-DD HH:mm:ss");
                let dayOfWeek = dt.format('dddd');
                let chosen_date = dt.format('YYYY-MM-DD');
                for(let i = 0; i<this.appointments.length; i++){
                    if(this.appointments[i].date === chosen_date){
                        if(this.time === this.appointments[i].time){
                            this.error = 'The chosen time is unavailable';
                            this.time = '';
                        }
                    }
                }
                if(this.time !== ''){
                    this.error = '';
                }

                    if(dayOfWeek == "Monday"){
                        this.optionsTime.start = this.schedule.data[0].Monday;
                        this.optionsTime.step = '00:30';
                        this.optionsTime.end = this.schedule.data[1].Monday;
                    }
                    if(dayOfWeek == "Tuesday"){
                        this.optionsTime.start = this.schedule.data[0].Tuesday;
                        this.optionsTime.step = '00:30';
                        this.optionsTime.end = this.schedule.data[1].Tuesday;
                    }
                    if(dayOfWeek == "Wednesday"){
                        this.optionsTime.start = this.schedule.data[0].Wednesday;
                        this.optionsTime.step = '00:30';
                        this.optionsTime.end = this.schedule.data[1].Wednesday;
                    }
                    if(dayOfWeek == "Thursday"){
                        this.optionsTime.start = this.schedule.data[0].Thursday;
                        this.optionsTime.step = '00:30';
                        this.optionsTime.end = this.schedule.data[1].Thursday;
                    }
                    if(dayOfWeek == "Friday"){
                        this.optionsTime.start = this.schedule.data[0].Friday;
                        this.optionsTime.step = '00:30';
                        this.optionsTime.end = this.schedule.data[1].Friday;
                    }
                },
            time: function () {
                let dt = moment(this.date, "YYYY-MM-DD HH:mm:ss");
                let chosen_date = dt.format('YYYY-MM-DD');
                for(let i = 0; i<this.appointments.length; i++){
                    if(this.appointments[i].date === chosen_date){
                        if(this.time === this.appointments[i].time){
                            this.error = 'The chosen time is unavailable';
                            this.time = '';
                        }
                    }
                }
                if(this.time !== ''){
                    this.error = '';
                }

            }
            }

    }
</script>