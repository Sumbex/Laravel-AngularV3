import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { NgbModalConfig, NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
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

  success_visible:boolean= false;
  txt:string='';
  failed_visible:boolean=false;

  modalReference = null; 
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];

  datosInicioMes: InicioCierre ={
     anio: '',
     mes: '',
     monto: '',
     inicial: ''
  }

  //variables-----------------
  load:boolean=false;
  monto:string='';
  anio:string='';
  mes:string='';
  public url: string;
  tabla:object=[];
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  //-------------------------

  //datos_validacion_usuario-
  user:object=[];
  rut:string='';
  password:string='';
  //-------------------------

  constructor(config: NgbModalConfig, private modalService: NgbModal,public _http: HttpClient,public _anios: AniosService) {

    config.backdrop = 'static';
    config.keyboard = false;

    //global---------------
    this.url = global.url;
    //---------------------
    
  }

  ngOnInit() {




  }

  openMensual(Mensual) {
    this.llenar_anio_y_mes();
    this.modalService.open(Mensual, { size: 'lg' });
    this.anio_actual();
    this.mes_actual();
    this.usuario_logeado();

    //console.log(document.getElementById('anio').options.value);
    
  }
  llenar_anio_y_mes(){


       this._anios.getAnios().subscribe((val) => {
              this.selectAnio = val;
              
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });

        this._anios.getMeses().subscribe((val) => {
              this.selectMes = val;
                
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
        

       //Cargar Años
    //this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    //this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }


  //metodos---------------------------------------------------------------------------------------
  calcular_cierre_mensual(anio, mes){
        this.failed_visible = false;
        this.success_visible = false;
        
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
                this.listar_tabla();
             
              
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

            this.failed_visible = false;
            this.success_visible = false;

            const formData = new FormData();
            formData.append('anio', this.anio);
            formData.append('mes', this.mes );
            formData.append('cierre_mensual', this.monto);
            this._http.post(this.url + "guardar_inicio_mensual", formData,
                        {headers: new HttpHeaders({'Authorization': 'Bearer' + this.token })}
              ).subscribe((val) => {
                  
                  if (val['estado'] == 'failed'){ 
                    console.log(val);
                    this.failed_visible = true;
                    this.success_visible = false;
                    this.txt = val['mensaje'];
                    this.listar_tabla();
                 
                  }
                   if (val['estado'] == 'success'){ 
                    console.log(val);
                    this.failed_visible = false;
                    this.success_visible = true;
                    this.txt = val['mensaje'];
                    this.listar_tabla();
                 
                  }
                  
              }, response => {console.log("POST call in error", response);},() => {
                     console.log("The POST success.");
              });


  }

  listar_tabla() {
        this._http.get(this.url + "listar_inicio_y_cierre_mensual_cs/"+this.anio ,{headers: new HttpHeaders(
                {'Authorization': 'Bearer' + this.token})}
        ).subscribe((val : object ) => {
            
            this.tabla = val;

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
      }

    cambiar_anio(){
        this.listar_tabla();
    }

    usuario_logeado(){
      
      this._http.get(this.url + "usuario_logeado",{headers: new HttpHeaders(
                {'Authorization': 'Bearer' + this.token})}
        ).subscribe((val : object ) => {
            
            this.user = val;

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
    }

    validar_usuario(validar){
       this.modalReference = this.modalService.open(validar, { size: 'sm' });
    }

    btn_validar_usuario($rut, $password, validar){//btn que esta en el modal de validacion de usuario
        this.load = true;
        const formData = new FormData();
        formData.append('rut', $rut.value);
        formData.append('password', $password.value);

        this._http.post(this.url + "confirmar_usuario", formData,{headers: new HttpHeaders(
                {'Authorization': 'Bearer' + this.token})}
        ).subscribe((val) => {
            
            if(val > 0){//si tiene acceso;
              this.guardar();
              this.load = false;
              // this.modalReference.close();
              //this.modalService.dismissAll();
             this.modalReference.close();
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.modalReference.close();
            }

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
    }

    private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return  `with: ${reason}`;
    }
  }

}
