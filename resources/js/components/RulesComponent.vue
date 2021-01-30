<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Welcome</div>

          <div class="conatiner">
            <div class="col-lg-12 mt-3">
              <p><strong>Rules Configuration</strong></p>
            <form @submit.prevent="submit">
                <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Show / Don't Show</th>
                    <th scope="col">Rules</th>
                    <th scope="col">Domain Details</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in rows">
                        <input  type="hidden" class="form-control" placeholder="Value" v-model="row.id">
                        <td>{{index+1}}</td>
                        <td>
                            <select name="" id="" class="form-control" v-model="row.show_hide_value">
                                <option :value="option.value" v-for="option in options" :key="option.id">{{ option.label }}</option>
                            </select>
                        </td>
                        <td>
                            <select name="rule" id="" class="form-control" v-model="row.rule_value" required>
                                <option disabled value="">Select Rule</option>
                                <option :value="option2.value" v-for="option2 in options2" :key="option2.id">{{ option2.label }}</option>
                            </select>
                        </td>
                        <td>
                             <div style="display:flex">
                                <a href="javascript:void(0" style="width:30%;margin-top:2%"> www.domain.com/ </a>
                                <input style="width:70%" type="text" class="form-control" placeholder="Value" v-model="row.domain_value">
                             </div>
                        </td>
                        <td style="text-align: center;">
                            <button
                               v-if="index > 0"
                                type="button"
                                @click="deleteRow(index)"
                                class="btn btn-outline-danger rounded-circle">
                                <strong>X</strong>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="vertical-align:bottom">
                            <div style="margin-left: 15px;">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" v-model="checked" checked="checked">
                                <label class="form-check-label" for="flexCheckChecked">
                                    <strong>Checkbox (Show alert with message when leaving a page)</strong>
                                </label>
                            </div>
                        </td>
                        <td colspan="2">
                            <input type="text" class="form-control" placeholder="Message" v-model="checked_message" :required="checked ? true : false">
                        </td>
                    </tr>
                   <tr>
                        <td colspan="2" style="vertical-align:bottom"><label><strong>Message (JS Alert)</strong></label></td>
                        <td colspan="3">
                            <input type="text" class="form-control" placeholder="Message" v-model="message">
                        </td>
                    </tr>
                    <tr  v-if="token">
                        <td colspan="5" style="vertical-align:bottom">
                            <h6>Include the follwoing javascript code in your application, inside head tag. </h6>
                            <div class="scriptTagDiv">&lt;script src="http://ec2-3-83-46-88.compute-1.amazonaws.com/poptin-task/public/js/script.js?token={{token}}"&gt;&lt;/script&gt;</div>
                        </td>
                    </tr>
                </tbody>
                </table>
                <div class="form-group row">
                    <div class="col-lg-12 text-right">
                    <button type="button" @click="addRow" class="btn btn-success">
                        Add Rule
                    </button>
                    <button class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Toasts :max-messages="5" :time-out="3000"></Toasts>
    <div v-if="loader" id="overlay">
        <div class="w-100 d-flex justify-content-center align-items-center">
            <div class="spinner"></div>
        </div>
    </div>
  </div>
</template>

<script>
import VueBootstrapToasts from "vue-bootstrap-toasts";
Vue.use(VueBootstrapToasts);
let BaseUrl = '/poptin-task/public/';
export default {
    created() {
        this.getRules();
    },
    data: function() {
        return  {
            options: [
                { 'label': 'Show on', 'value': 'show'},
                { 'label': `Don't Show on`, 'value': 'hide'},
            ],
            options2: [
                { 'label': 'Pages that contains', 'value': 'contains'},
                { 'label': 'Pages starting with', 'value': 'startsWith'},
                { 'label': 'Pages ending with', 'value': 'endsWith'},
                { 'label': 'A Specific page', 'value': 'exact'},
            ],
            rows: [],
            message:'',
            loader: false,
            token :"",
            checked: false,
            checked_message : '',
        }
    },

  methods: {
    addRow: function() {
        this.rows.push({'id': 0, 'show_hide_value': 'show', 'rule_value': '', 'domain_value': ''});
    },
    deleteRow: function(index) {
        this.rows.splice(index, 1);
    },

    submit() {
        this.loader = true;
        let data = [];
        data.push({
            'rows' : this.rows,
            'message' : this.message,
            'checked' : this.checked,
            'checked_message' : this.checked_message
        });
        // /poptin-task/public/ruleSubmission
        axios.post(BaseUrl+'/ruleSubmission',data).then(response => {
        setTimeout(() => {
            this.$toast.success('Rule added successfully');
            this.loader = false;
            this.rows = [];
            this.getRules();
        },1000);
        
        }).catch(error => {
          this.loader = false;
          if (error.response.status === 422) {
              debugger;
            // this.errors = error.response.data.errors || {};
          }
        });
    },


    getRules() {
        // /poptin-task/public/getRules
        axios.get(BaseUrl+'/getRules').then(response => {
          if(response.data.length > 0) {
              this.rows.push.apply(this.rows,response.data);
              this.message = response.data[0]['user']['alert_message'];
              this.checked = response.data[0]['user']['checked'];
              this.checked_message = response.data[0]['user']['checked_message'];
              this.token = response.data[0]['user']['unique_token'];
          } else {
            this.addRow();
          }
        });
    }
  },
};
</script>
