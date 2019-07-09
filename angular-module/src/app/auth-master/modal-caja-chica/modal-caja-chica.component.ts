import { Component, OnInit, ViewChild } from '@angular/core';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Definicion } from 'src/app/modelos/definicion.model';
import { CajaChicaService } from 'src/app/servicios/caja-chica.service';
import { TablaCajaChicaComponent } from './tabla-caja-chica/tabla-caja-chica.component';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { all } from 'q';

@Component({
  selector: 'app-modal-caja-chica',
  templateUrl: './modal-caja-chica.component.html',
  styleUrls: ['./modal-caja-chica.component.css']
})
export class ModalCajaChicaComponent implements OnInit {
  @ViewChild(TablaCajaChicaComponent, { static: false }) private tablaComponent: TablaCajaChicaComponent;

  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  cajaChica: cajaChicaSindical[] = [];
  cajaChicaError: boolean = false;

  errorIngreso = false;
  ingresoStatus;

  selectDefinicion: Definicion[] = [];

  valorAnio: Anios = {
    descripcion: '1'
  }

  valorMes: Meses = {
    descripcion: '1'
  }

  datosCajaChica: cajaChicaSindical = {
    numero_documento: 0,
    archivo_documento: '',
    fecha: '',
    descripcion: '',
    definicion: '2',
    monto_ingreso: 0,
    monto_egreso: 0
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _cajaChicaService: CajaChicaService) {
    config.backdrop = 'static';
    config.keyboard = false;

  }

  ngOnInit() {

    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar Caja chica
    this.refrescarCajaChica();

    //Cargar definiciones
    this.selectDefinicion = JSON.parse(localStorage.getItem('definicion'));
  }

  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'lg' });
  }

  changeDefinicion(evento) {
    this.datosCajaChica.definicion = evento.target.value;
    console.log("ID definicion: " + this.datosCajaChica.definicion);
  }

  changeAnio(evento) {
    this.valorAnio.descripcion = evento.target.value;
    console.log("ID anio: " + this.valorAnio.descripcion);
    this.cajaChicaError= false;
    this.refrescarCajaChica();
  }

  changeMes(evento) {
    this.valorMes.descripcion = evento.target.value;
    console.log("ID mes: " + this.valorMes.descripcion);
    this.cajaChicaError= false;
    this.refrescarCajaChica();
  }

  onSubmit({ value, valid }: { value: cajaChicaSindical, valid: boolean }) {
    if (!valid) {
      console.log("Ingreso no valido revisar campos");
    } else {
      this._cajaChicaService.ingresarValor(this.datosCajaChica).subscribe(
        response => {
          if(response.estado == 'failed'){
            console.log("Capturado el failed");
            this.ingresoStatus = JSON.stringify(response.mensaje);
            console.log(this.ingresoStatus);
            this.errorIngreso = true;
          }else{
            console.log("Ingreso correcto");
            this.errorIngreso = false;
            this.refrescarCajaChica();
          }
            //console.log("test");
            //console.log(response);
            //console.log(response.mensaje);
            //console.log("test");
        },
        error => {
          console.log(<any>error);
        }
      );
    }
  }

  refrescarCajaChica() {
    //Cargar Caja chica
    this._cajaChicaService.getCajaChica(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
      response => {
        if (response.estado != "failed") {
          this.cajaChica = response;
          console.log("refrescando caja chica");
          console.log(this.cajaChica);
        } else {
          console.log(response);
          this.cajaChicaError = true;
        }
      },
      error => {
        console.log(error);
      }
    );
  }

}
