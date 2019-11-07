import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from '../servicios/sindical.service';
import { AniosService } from '../servicios/anios.service';

@Component({
  selector: 'app-modal-gastos-operacionales',
  templateUrl: './modal-gastos-operacionales.component.html',
  styleUrls: ['./modal-gastos-operacionales.component.css']
})
export class ModalGastosOperacionalesComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;
  cargarDatos = 0;

  //Datos del Formulario
  datosFormulario={
    numero_documento: '',
    archivo_documento: null,
    fecha: '',
    descripcion: '',
    definicion: '2',
    monto: null
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _service: SindicalService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if(this.cargarDatos == 2){
          //this.cargarTablaCajaChica();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.idMesActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if(this.cargarDatos == 2){
          //this.cargarTablaCajaChica();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  openModal(CajaChica) {
    this.modalService.open(CajaChica, { size: 'xl' });
    this.cargarFechasActuales();
  }

  onSelectImage(event) {
    this.datosFormulario.archivo_documento = event.srcElement.files[0];
  }

  cargarTablaCajaChica(){
    this.cargandoTabla = true;
    this._service.getTablaSindical(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        console.log(response);
        this.cargandoTabla = false;
      }
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  ingresarValor(){
    console.log(this.datosFormulario);
    /* this._service.calcular_cuota('test').subscribe(response=>{
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
      }
    }, error=>{
      console.log(error);
    }); */
  }

}
