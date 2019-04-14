<template>
    <b-modal  ref="modalfirst" size="lg" title="First Time Customization" hide-footer="true"
              :close-on-backdrop="false" :close-on-esc="false"
              aria-hidden="true">
        <div slot="modal-header"></div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Your Position</label>
            <div class="col-9">
                <select class="form-control" v-model="position_id" @change="getDepartment()">
                    <option v-for="position in $common.data.positions" :value="position.id">{{position.name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row" v-if="position_id != ''">
            <label class="col-3 col-form-label">Department</label>
            <div class="col-9">
                <select class="form-control" v-model="department_id" @change="getHospital()">
                    <option v-for="department in departments" :value="department.id">{{department.name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row" v-if="department_id != ''">
            <label class="col-3 col-form-label">Hospital</label>
            <div class="col-9">
                <select class="form-control" v-model="hospital_id">
                    <option v-for="hospital in hospitals" :value="hospital.id">{{hospital.name}}</option>
                </select>
            </div>
        </div>


        <div class="form-group" v-if="hospital_id != ''">
            <p class="text-danger" v-if="error_schedule">{{error_schedule}}</p>
            <div class="form-group row">
                <label class="col-1"><b-form-checkbox switch v-model="schedule.monday.isMonday" value="true"></b-form-checkbox></label>
                <label class="col-3 col-form-label">Monday</label>
                <div class="col-4">
                    <date-picker :disabled="!schedule.monday.isMonday" value-type="format" v-model="schedule.monday.from" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
                <div class="col-4">
                    <date-picker :disabled="!schedule.monday.isMonday" value-type="format" v-model="schedule.monday.to" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1"><b-form-checkbox switch v-model="schedule.tuesday.isTuesday" value="true"></b-form-checkbox></label>
                <label class="col-3 col-form-label">Tuesday</label>
                <div class="col-4">
                    <date-picker :disabled="!schedule.tuesday.isTuesday" value-type="format" v-model="schedule.tuesday.from" lang="en" type="time" format="HH:mm:ss"></date-picker>                </div>
                <div class="col-4">
                    <date-picker :disabled="!schedule.tuesday.isTuesday" value-type="format" v-model="schedule.tuesday.to" lang="en" type="time" format="HH:mm:ss"></date-picker>                </div>
            </div>
            <div class="form-group row">
                <label class="col-1"><b-form-checkbox switch v-model="schedule.wednesday.isWednesday" value="true"></b-form-checkbox></label>
                <label class="col-3 col-form-label">Wednesday</label>
                <div class="col-4">
                    <date-picker :disabled="!schedule.wednesday.isWednesday" value-type="format" v-model="schedule.wednesday.from" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
                <div class="col-4">
                    <date-picker :disabled="!schedule.wednesday.isWednesday" value-type="format" v-model="schedule.wednesday.to" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1"><b-form-checkbox switch v-model="schedule.thursday.isThursday" value="true"></b-form-checkbox></label>
                <label class="col-3 col-form-label">Thursday</label>
                <div class="col-4">
                    <date-picker :disabled="!schedule.thursday.isThursday" value-type="format" v-model="schedule.thursday.from" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
                <div class="col-4">
                    <date-picker :disabled="!schedule.thursday.isThursday" value-type="format" v-model="schedule.thursday.to" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1"><b-form-checkbox switch v-model="schedule.friday.isFriday" value="true"></b-form-checkbox></label>
                <label class="col-3 col-form-label">Friday</label>
                <div class="col-4">
                    <date-picker :disabled="!schedule.friday.isFriday" value-type="format" v-model="schedule.friday.from" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
                <div class="col-4">
                    <date-picker :disabled="!schedule.friday.isFriday" value-type="format" v-model="schedule.friday.to" lang="en" type="time" format="HH:mm:ss"></date-picker>
                </div>
            </div>
        </div>
        <br>
        <button
                type="button"
                class="btn btn-primary"
                @click="sendData"
                :disabled="hospital_id == ''">
            <i v-show="formSending" class="fa fa-spinner fa-spin"></i> Select
        </button>
    </b-modal>
</template>

<script>

    import { post, get } from '../../../helpers/api'
    import Checkbox from "vue-strap/src/Checkbox";

    export default {
        components: {Checkbox},
        props:['user'],

        data(){
            return {
                position_id:'',
                department_id:'',
                hospital_id:'',
                departments:[],
                hospitals:[],
                error_schedule:false,
                schedule:{
                    monday:{
                        isMonday:false,
                        from:'',
                        to:''
                    },
                    tuesday:{
                        isTuesday:false,
                        from:'',
                        to:''
                    },
                    wednesday:{
                        isWednesday:false,
                        from:'',
                        to:''
                    },
                    thursday:{
                        isThursday:false,
                        from:'',
                        to:''
                    },
                    friday:{
                        isFriday:false,
                        from:'',
                        to:''
                    },
                }
            }
        },
        methods: {
            showModal() {
                this.$refs.modalfirst.show();
                console.log(this.user);
            },
            hideModal() {
                this.$refs.modalfirst.hide();
            },

            getDepartment(){
                let _this = this;
                post(
                    _this,
                    'api/doctor/departments',
                    {position: _this.position_id},
                    function(response){
                        _this.departments = response.data;
                    },
                    function (error) {

                    }
                );
            },

            getHospital(){
                let _this = this;
                post(
                    _this,
                    'api/doctor/hospitals',
                    {department: _this.department_id},
                    function(response){
                        _this.hospitals = response.data;
                    },
                    function (error) {

                    }
                );
            },

            sendData(){
                if((this.schedule.monday.isMonday == 'true' && (this.schedule.monday.from == '' || this.schedule.monday.to == ''))
                || (this.schedule.tuesday.isTuesday == 'true' && (this.schedule.tuesday.from == '' || this.schedule.tuesday.to == '')) ||
                    (this.schedule.wednesday.isWednesday == 'true' && (this.schedule.wednesday.from == '' || this.schedule.wednesday.to == ''))
                || (this.schedule.thursday.isThursday == 'true' && (this.schedule.thursday.from == '' || this.schedule.thursday.to == ''))
                || (this.schedule.friday.isFriday == 'true' && (this.schedule.friday.from == '' || this.schedule.friday.to == ''))){

                    this.error_schedule = 'Wrong schedule format';
                    return;
                }

                if((this.schedule.monday.isMonday == 'true' && (this.schedule.monday.from > this.schedule.monday.to))
                    || (this.schedule.tuesday.isTuesday == 'true' && (this.schedule.tuesday.from > this.schedule.tuesday.to)) ||
                    (this.schedule.wednesday.isWednesday == 'true' && (this.schedule.wednesday.from > this.schedule.wednesday.to))
                    || (this.schedule.thursday.isThursday == 'true' && (this.schedule.thursday.from > this.schedule.thursday.to))
                    || (this.schedule.friday.isFriday == 'true' && (this.schedule.friday.from > this.schedule.friday.to))){

                    this.error_schedule = 'Wrong schedule format';
                    return;

                }

                let _this = this;

                post(
                    _this,
                    '/api/doctor/save-info',
                    {
                       department:_this.department_id,
                       position:_this.position_id,
                       schedule:_this.schedule,
                        user:_this.$root.user.data.id
                    },
                    function(response){
                        _this.hideModal();
                    },
                    function(error){

                    }
                );
            },

        },

        mounted(){
            this.$emit('ready');
        }

    }
</script>