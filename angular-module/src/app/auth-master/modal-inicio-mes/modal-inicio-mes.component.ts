import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { InicioCierre } from 'src/app/modelos/inicioCierre.model';
//import-------------
import { global } from '../../servicios/global';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-modal-inicio-mes',
  templateUrl: './modal-inicio-mes.component.html',
  styleUrls: ['./modal-inicio-mes.component.css']
})
export class ModalInicioMesComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];

  datosInicioMes: InicioCierre ={
     anio: '',
     mes: '',
     monto: '',
     inicial: ''
  }

  //variables-----------------
  monto:string='';
  anio:string='';
  mes:string='';
  public url: string;
  tabla:object=[];
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  //-------------------------

  constructor(config: NgbModalConfig, private modalService: NgbModal,public _http: HttpClient) {

    config.backdrop = 'static';
    config.keyboard = false;

    //global---------------
    this.url = global.url;
    //---------------------
    
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));


  }

  openMensual(Mensual) {
    this.modalService.open(Mensual, { size: 'lg' });
    this.anio_actual();
    this.mes_actual();
    //console.log(document.getElementById('anio').options.value);
  }

  //metodos---------------------------------------------------------------------------------------
  calcular_cierre_mensual(anio, mes){

        this._http.get(this.url + "calcular_cm/"+this.anio+"/"+this.mes,
                    {headers: new HttpHeaders({'Authorization': 'Bearer' + this.token })}
          ).subscribe((val:string) => {
              this.monto = val;
              console.log(val);
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
  }
  anio_actual(){
        this._http.get(this.url + "anio_actual",
                    {headers: new HttpHeaders({'Authorization': 'Bearer' + this.token })}
          ).subscribe((val : { id, descripcion } ) => {

                this.anio = val.id;
             
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
  }
  mes_actual(){
        this._http.get(this.url + "mes_actual",
                    {headers: new HttpHeaders({'Authorization': 'Bearer' + this.token })}
          ).subscribe((val : { id, descripcion } ) => {

                this.mes = val.id;
             
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
  }
  guardar(){
        const formData = new FormData();
        formData.append('anio', this.anio);
        formData.append('mes', this.mes );
        formData.append('cierre_mensual', this.monto);
        this._http.post(this.url + "guardar_inicio_mensual", formData,
                    {headers: new HttpHeaders({'Authorization': 'Bearer' + this.token })}
          ).subscribe((val) => {
              this.monto = val+'';
              console.log(val);
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });


  }

}
