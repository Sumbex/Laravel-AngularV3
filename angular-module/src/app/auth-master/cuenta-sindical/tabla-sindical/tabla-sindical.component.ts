import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { Definicion } from 'src/app/modelos/definicion.model';

@Component({
  selector: 'app-tabla-sindical',
  templateUrl: './tabla-sindical.component.html',
  styleUrls: ['./tabla-sindical.component.css']
})
export class TablaSindicalComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  idAnioActual;
  idMesActual;
  loading = false;
  //TABLAS
  tablaSindical;
  fijos;
  variable;
  cajaChica;
  prestamo;
  camping;
  resultado:any=["total_final"];

  verify:boolean = false;
  get_numero:number = 0;
  //cierre del mes anterior
  cierreAnterior;

  //actualizar
  entrada;
  archivoDocumento;
  selectDefinicion: Definicion[] = [];
  modalActualizar = null;

  valorAnio: Anios = {
    descripcion: ''
  }

  valorMes: Meses = {
    descripcion: ''
  }

  //actualizar caja chica
  actualizarMontoCajaChica;

  suc_res1 = false;
  suc_res2 = false;

  //mensajes de alerta
  alertMensaje;
  alertEstado = false;


  constructor(config: NgbModalConfig, private modalService: NgbModal, private _sindicalService: SindicalService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;

  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    
    //Cargar definiciones
    this.selectDefinicion = JSON.parse(localStorage.getItem('definicion'));

  }

  cargar_select(){

     //Cargar id del Año actual
     this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response;
        this.valorAnio.descripcion = this.idAnioActual.id; 
        this.suc_res1 = true;
        
        this.listo_para_listar(this.suc_res1, this.suc_res2);

      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.idMesActual = response;
        this.valorMes.descripcion = this.idMesActual.id;
        this.suc_res2 = true;
              
        this.listo_para_listar(this.suc_res1, this.suc_res2);
      },
      error => {
        console.log(error);
      }
    )

  }

  listo_para_listar(res1, res2){
    if (res1 == true && res2 == true) {
      this.refrescarSindical();
      this.cierreMensualAnterior();
    }
  }

  changeAnio(evento) {
    this.valorAnio.descripcion = evento.target.value;

    
    this.cierreMensualAnterior();
    this.refrescarSindical();
  }

  changeMes(evento) {
    this.valorMes.descripcion = evento.target.value;

    
    this.cierreMensualAnterior();
    this.refrescarSindical();
  }

  openTablaSindical(TablaSindical) {
    this.modalService.open(TablaSindical, { size: 'lg' });
    this.cargar_select();
  }

  refrescarSindical() {
    this.tablaSindical = [];
          this.fijos = [];
          this.variable = [];
          this.cajaChica = [];
          this.prestamo = [];
          this.camping = [];
          this.resultado = [];
    this._sindicalService.getTablaSindical(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
      response => {
        if (response == null) {
          this.tablaSindical = [];
          this.fijos = [];
          this.variable = [];
          this.cajaChica = [];
          this.prestamo = [];
          this.camping = [];
          this.resultado = [];
        }else{
          this.tablaSindical = response;
          this.fijos = this.tablaSindical.fijo;
          this.variable = this.tablaSindical.variable;
          this.cajaChica = this.tablaSindical.caja_chica;
          this.prestamo = this.tablaSindical.prestamo;
          this.camping = this.tablaSindical.camping;
          this.resultado = this.tablaSindical.resultado;
        }
        this.loading = false;
      },
      error => {
        console.log(error);
      }
    );
    this.loading = true;
  }
  cierreMensualAnterior(){
      this.cierreAnterior = [];
        this._sindicalService.getTablaSindicalMontoInicial(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
          response => {
            if (response['estado'] == "failed") {
              this.get_numero = 0;
            }
            if(response['estado'] == "success"){
              this.cierreAnterior = response[0].inicio_mensual;
              this.get_numero = this.cierreAnterior;
            }
          }
        );
  }

  openActualizar(Actualizar) {
   this.modalActualizar = this.modalService.open(Actualizar,{ size: 'sm' });
   
  }
  cerrarActualizar(){
    this.modalActualizar.close();
    this.alertEstado = false;
    this.alertMensaje = "";
  }

  //actualizar items
  actualizar(id,campo,input){
    if(campo == "archivo"){
     this.entrada = this.archivoDocumento; 
    }else{
      this.entrada = input.value;
    }
    if(this.entrada == ''){
      this.alertEstado = true;
      this.alertMensaje = ("ingrese datos porfavor!");
      return false;
    }
    this._sindicalService.getTablaSindicalActualizar(id,campo,this.entrada).subscribe(
      response => {
        if(response.estado == "success"){
          this.alertEstado = true;
          this.alertMensaje = response.mensaje;
          //this.modalActualizar.close();
          this.alertEstado = false;
          this.alertMensaje = "";
        }
        if(response.estado == "failed"){
          this.alertEstado = true;
          this.alertMensaje = response.mensaje;
          this.alertEstado = false;
          this.alertMensaje = "";
          
          
        }
      }
    )

    this.cierreMensualAnterior();
    this.refrescarSindical();

  }

  onSelectImage(event) {
     this.archivoDocumento = event.srcElement.files[0];
  }

  actualizarCaja(){
    this._sindicalService.getCalcularCajaChicaActualizar(this.valorAnio.descripcion,this.valorMes.descripcion).subscribe(
      response => {
        //console.log(response);
        if(response.estado == "success"){
          if(response.monto == 0){
            alert("no existe monto el mes anterior");
            response.monto = " ";
          }
          this.actualizarMontoCajaChica = response.monto;
        }else{
          this.actualizarMontoCajaChica = null;
        }
      },
      error => {
        console.log(<any>error);
      }
    );
  }

}
