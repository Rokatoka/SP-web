<template>

    <div>

        <div class="col-2 offset-2 fixed-top h-100 pt-4" v-if="history.appointment.status == 'approved'">
            <button type="button" class="btn btn-danger btn-block text-left btn-sm" @click="cancelAppointment"><span class="fa fa-fw fa-trash"></span> Cancel</button>
        </div>

        <div class="col-2 offset-2 fixed-top h-100 pt-4" v-if="history.appointment.status == 'canceled'">
            Appointment was Canceled
        </div>

        <div class="col-8 offset-4 pr-5">
            <div class="mb-5 mt-1">
                <span class="h4"></span>
            </div>

            <div class="h5">Appointment Information</div>
            <table class="table table-responsive" v-if="history.appointment.status != 'canceled'">
                <tbody>
                <tr>
                    <td><span class="h6">Doctor</span></td>
                    <td>{{history.doctor.user.first_name}} {{history.doctor.user.last_name}} {{history.doctor.user.patronymic}}</td>
                </tr>
                <tr>
                    <td><span class="h6">Diagnosis</span></td>
                    <td v-if="history.diagnosis">{{history.diagnosis}}</td>
                    <td v-else>No Data</td>
                </tr>
                <tr>
                    <td><span class="h6">Analysis</span></td>
                    <td v-if="history.analysis">{{history.analysis}}</td>
                    <td v-else>No Data</td>
                </tr>
                <tr>
                    <td><span class="h6">Treatment</span></td>
                    <td v-if="history.treatment">{{history.treatment}}</td>
                    <td v-else>No Data</td>
                </tr>
                <tr>
                    <td><span class="h6">Hospital</span></td>
                    <td>{{history.department.hospital.name}}</td>
                </tr>
                <tr>
                    <td><span class="h6">Department</span></td>
                    <td>{{history.department.name}}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <customize-modal ref="customizeModal" :appointment="this.id" v-on:upd="getItem"></customize-modal>

    </div>

</template>

<script>
    import { del, get } from '../../helpers/api'

    export default {

        props:['id'],

        data(){
            return{
                history:{}
            }
        },

        components:{

        },

        methods:{
            getItem(){
                let _this = this;
                get(
                    _this,
                    '/api/patient/get-appointment-history/' + _this.id,
                    {},
                    function (response) {
                        _this.history = response.data;
                    },
                    function (error) {

                    }
                );
            },

            cancelAppointment(){
                let _this = this;
                get(
                    _this,
                    '/api/patient/cancel-appointment/' + _this.history.appointment.id,
                    {},
                    function (response) {
                        _this.history = response.data;
                    },
                    function (error) {

                    }
                );
            }
        },

        created() {
            this.getItem();
            console.log(this.id);
        }

    }
</script>