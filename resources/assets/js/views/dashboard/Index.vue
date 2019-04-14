<template>
    <div class="col-10 offset-2">
        <div class="row">
            <div class="total">
                <h2>Total Users</h2>
                <span style="font-size: 250%;">{{total}}</span>
            </div>
            <div>
                <h3>Male/Female Users</h3>
                <pie-chart :data="[['Males', malesTot], ['Females', femalesTot]]" :width="5" :height="1" :download="true"></pie-chart>
            </div>
            <div>
                <h3>Doctor/Patient Users</h3>
                <pie-chart :data="[['Doctor', doctors], ['Patient', patients]]" :width="5" :height="1" :download="true"></pie-chart>
            </div>
        </div>
    </div>
</template>


<script>

    import { get } from '../../helpers/api'

    export default {

        data() {
            return {
                total:'',
                femalesTot:'',
                malesTot:'',
                doctors:'',
                patients:''
            }
        },
        methods: {
        },

        mounted() {
            let _this = this;
            get(
                _this,
                '/api/statistics',
                {},
                function (res) {
                    _this.total = res.data.total;
                    _this.femalesTot = res.data.femalesTot;
                    _this.malesTot = res.data.malesTot;
                    _this.doctors = res.data.doctors;
                    _this.patients = res.data.patients;
                },
                function (err) {

                }
            );
        }

    }

</script>

<style scoped>
    .total{
        width:200px;
        margin-left: 30px;
        margin-right: 200px;
    }
</style>