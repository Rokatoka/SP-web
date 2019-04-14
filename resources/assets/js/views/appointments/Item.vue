<template>

    <div>

        <div class="col-2 offset-2 fixed-top h-100 pt-4">
            <button type="button" class="btn btn-secondary btn-block text-left btn-sm" @click="$refs.customizeModal.showModal()"><span class="fa fa-fw fa-pencil"></span> Customize</button>
        </div>

        <div class="col-8 offset-4 pr-5">
            <div class="mb-5 mt-1">
                <span class="h4"></span>
            </div>

            <div class="h5">Appointment Information</div>
            <table class="table table-responsive">
                <tbody>
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
            'customize-modal': require('./modals/Customize.vue'),
        },

        methods:{
            getItem(){
                let _this = this;
                get(
                    _this,
                    '/api/doctor/get-appointment-history/' + _this.id,
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