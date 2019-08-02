import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from '../../servicios/anios.service';
import { CsDetalleCampingService } from '../../servicios/cs-detalle-camping.service';
import { ValidarUsuarioService } from '../../servicios/validar-usuario.service';

@Component({
  selector: 'app-modal-camping',
  templateUrl: './modal-camping.component.html',
  styleUrls: ['./modal-camping.component.css']
})
export class ModalCampingComponent implements OnInit {

  suc_res1 = false;
  suc_res2 = false;
  anio:string='';
  anios;
  mes:string='';
  meses;

  detallecamping;
  resumen:any = [ "ingreso", "egreso","total" ];
  monto;


  modalReference = null; 
  load:boolean=false;
  user:object=[];

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal, 
    private _anios:AniosService, 
    private _camping:CsDetalleCampingService,
    private _validarusuario:ValidarUsuarioService
  ) {

    config.backdrop = 'static';
    config.keyboard = false;

  }
  ngOnInit() {
    //Cargar Años
      this.anios = JSON.parse(localStorage.getItem('anios'));

      //Cargar Meses
      this.meses = JSON.parse(localStorage.getItem('meses'));


  }

  openCamping(Camping) {
    this.modalService.open(Camping, { size: 'lg' });
    this.cargar_select();
    this.usuario_logeado();
    //this.listo_para_listar();

  }

  cargar_select(){
     
      this._anios.getAnioActual().subscribe((val: {descripcion, id}) => {
              this.anio = val.id;
              this.suc_res1 = true;

              this.listo_para_listar(this.suc_res1, this.suc_res2);
              
                
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
      
      this._anios.getMesActual().subscribe((val:{descripcion, id}) => {
              this.mes = val.id;
              this.suc_res2 = true;
              
              this.listo_para_listar(this.suc_res1, this.suc_res2);
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });

  }

  listar_detalle_camping(){
    // listar_detalle_camping/{anio}/{mes}
        this._camping.getLista(this.anio, this.mes).subscribe((val) => {
            if(val.estado == "success"){
              this.detallecamping = val.tabla;
              this.resumen = val.resumen;
            }
            if(val.estado == "failed"){
              alert("No existen registros en este periodo de tiempo");
              this.detallecamping = val.tabla;
              this.resumen = val.resumen;
            }
                
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
  }
  traer_monto(){
      this._camping.getMonto(this.anio, this.mes).subscribe((val) => {

              this.monto = val;

                            
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
  }
  guarda_monto(){

        if (this.monto == '') {

          alert("No ha ingresado un monto en este campo");
          return false;
        }

          this._camping.guardar(this.anio, this.mes, this.monto).subscribe((val) => {
              //this.detallecamping = val;
              if (val.estado == 'success') {
                alert(""+val.mensaje+"");
                this.listar_detalle_camping();
              }
                
              
          }, response => {console.log("POST call in error", response);},() => {
                 console.log("The POST success.");
          });
  }

  change_mes(){
      this.traer_monto();
      this.listar_detalle_camping();
  }
  change_anio(){
      this.traer_monto();
      this.listar_detalle_camping();
  }

  listo_para_listar(res1, res2){
    if (res1 == true && res2 == true) {
      console.log("ready to get");
      this.traer_monto();
      this.listar_detalle_camping();

    }
  }


  usuario_logeado(){
      
      this._validarusuario.usuario_logeado().subscribe((val : object ) => {
            
            this.user = val;

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
  }

  btn_validar_usuario($rut, $password, validar){//btn que esta en el modal de validacion de usuario
        this.load = true;
        const formData = new FormData();
        formData.append('rut', $rut.value);
        formData.append('password', $password.value);

        this._validarusuario.validar_usuario(formData).subscribe((val) => {
            
            if(val > 0){//si tiene acceso;
            
              this.load = false;
              
             this.modalReference.close();
             this.guarda_monto();
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.modalReference.close();
            }

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
    }



  validar_usuario(validar){
       this.modalReference = this.modalService.open(validar, { size: 'sm' });
  }
}
