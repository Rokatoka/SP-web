<template>
    <div class="col-10 offset-2">
        <filter-appointments ref="filter" v-on:filtered="filtered"></filter-appointments>
        <div class="col-8 offset-4">

            <table class="table mt-2">
                <thead class="thead-default">
                <tr>
                    <th>Patient</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="appointment in appointments">
                    <td>{{appointment.patient.user.first_name}} {{appointment.patient.user.last_name}}</td>
                    <td v-if="appointment.status == 'approved'"><p class="text-primary">{{appointment.status}}</p></td>
                    <td v-if="appointment.status == 'canceled'"><p class="text-warning">{{appointment.status}}</p></td>
                    <td v-if="appointment.status == 'done'"><p class="text-success">{{appointment.status}}</p></td>
                    <td v-if="appointment.status == 'missed'"><p class="text-danger">{{appointment.status}}</p></td>
                    <td>{{appointment.date}} {{appointment.time}}</td>
                    <td>
                        <div class="pull-right"  v-if="appointment.status != 'canceled'">
                            <b-tooltip title="Open">
                                <router-link :to="{name:'appointment', params:{id: appointment.id}}" class="btn btn-outline-primary btn-sm"><span class="fa fa-address-card fa-2x"></span></router-link>
                            </b-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <first-time ref="firstTime" :user="$root.user.data.id" v-on:ready="callFirst()"></first-time>
    </div>
</template>


<script>

    import { post, get } from '../../helpers/api'

    export default {

        data() {
            return {
                doctor:'',
                appointments:[]
            }
        },
        components:{
            'first-time': require('./modals/FirstTime.vue'),
            'filter-appointments': require('./Filter.vue')
        },
        methods: {

            callFirst(){

                let _this = this;
                get(
                    _this,
                    `/api/doctor/get/${_this.$root.user.data.id}`,
                    {},
                    function (response) {
                        _this.doctor = response.data;
                        if(_this.doctor.position_id == null){
                            _this.$refs.firstTime.showModal();
                        }
                        _this.getList();
                    },
                    function (error) {

                    }
                );

            },

            getList(){
                let _this = this;
                post(
                    _this,
                    '/api/doctor/get-appointments',
                    {
                        doctor: _this.doctor.id,
                        filter: _this.filterData
                    },
                    function (response) {
                        _this.appointments = response.data;
                        console.log(response.data);
                    },
                    function(error){

                    }
                );
            },

            filtered() {
                this.filterData = this.$refs.filter.filterData;
                console.log(this.filterData.search_text);

                this.$nextTick(function () {
                    this.getList();
                });

            },

        },

        created() {

        }

    }

</script>