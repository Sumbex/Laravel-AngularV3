import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';



@Component({
  selector: 'app-cambio-contrasenia',
  templateUrl: './cambio-contrasenia.component.html',
  styleUrls: ['./cambio-contrasenia.component.css']
})
export class CambioContraseniaComponent implements OnInit {
  load:boolean = false;
  success_visible:boolean = false;
  fail_visible:boolean = false;
  password:string = '';
  confirm_password:string = '';
  new_password:string = '';

  public url: string;
  token = localStorage.getItem('token').replace(/['"]+/g, '');




  constructor(
  		public _http: HttpClient, 
  		config: NgbModalConfig, 
  		private modalService: NgbModal, 

  ) { 
  	this.url = global.url;
  	config.backdrop = 'static';
    config.keyboard = false;

  }

  ngOnInit() {
  }

  openMensual(Mensual) {
    this.modalService.open(Mensual, { size: 'lg' });
  }

  send(password, confirm_password, new_password){

  	this.load = true;

  	var pass = password.value;
  	var confirm_pass = confirm_password.value;
  	var new_pass = new_password.value;

  	
  	const formData = new FormData();
  	formData.append('password', pass);
  	formData.append('confirm_password', confirm_pass );
  	formData.append('new_password', new_pass);


    this._http.post(this.url + "cambiar_password", formData,{headers: new HttpHeaders(
            {
                'Authorization': 'Bearer' + this.token,
                //'Content-Type': 'application/form-data'
            }
    )}).subscribe((val) => {

            if (val.estado == "success") {
            	this.password = '';
				this.confirm_password = '';
				this.new_password = '';
				this.success_visible = true;
				this.fail_visible = false;
				this.success_texto = 'Se ha actualizado la contraseña!.';
				this.load = false;
            	
            }
            if (val.estado == false) {
            	this.fail_visible = true;
            	this.success_visible = false;
            	
            	this.success_texto = val.mensaje;
            	this.load = false;

            }
        }, response => {
            console.log("POST call in error", response);
        },
        () => {
           console.log("The POST observable is now completed.");
    });





  }
  close(){
  	this.success_visible = false;
  	this.fail_visible = false;
  }


}
