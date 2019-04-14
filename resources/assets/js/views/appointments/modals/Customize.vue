<template>
    <b-modal  ref="modalCustomize" size="lg" title="Customize" hide-footer=true>
        <div class="form-group row">
            <label class="col-3 col-form-label">Diagnosis</label>
            <div class="col-9">
                <textarea class="form-control" v-model="diagnosis" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Analysis</label>
            <div class="col-9">
                <textarea class="form-control" v-model="analysis" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Treatment</label>
            <div class="col-9">
                <textarea class="form-control" v-model="treatment" rows="3"></textarea>
            </div>
        </div>

        <button
                type="button"
                class="btn btn-primary"
                @click="sendData">
            <i v-show="formSending" class="fa fa-spinner fa-spin"></i> Apply
        </button>
    </b-modal>
</template>


<script>
    import { post, get } from '../../../helpers/api'

    export default{

        props:['appointment'],

        data(){
            return{
                treatment:'',
                diagnosis:'',
                analysis:''
            }
        },

        components:{

        },

        methods:{

            sendData(){
                let _this = this;
                post(
                    _this,
                    '/api/doctor/appointment-customize',
                    {
                        treatment: _this.treatment,
                        diagnosis: _this.diagnosis,
                        analysis: _this.analysis,
                        appointment: _this.appointment,

                    },
                    function(response){
                        _this.$emit('upd');
                        _this.hideModal();
                    },
                    function (error) {

                    }
                );
            },

            showModal() {
                this.$refs.modalCustomize.show();
                console.log(this.appointment);

            },
            hideModal() {
                this.$refs.modalCustomize.hide();
            },
        }
    }
</script>