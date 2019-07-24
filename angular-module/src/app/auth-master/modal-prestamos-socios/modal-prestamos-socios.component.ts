import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { SociosService } from 'src/app/servicios/socios.service';
import { Prestamos } from 'src/app/modelos/prestamos.model';

@Component({
  selector: 'app-modal-prestamos-socios',
  templateUrl: './modal-prestamos-socios.component.html',
  styleUrls: ['./modal-prestamos-socios.component.css']
})
export class ModalPrestamosSociosComponent implements OnInit {

  selectTipoPrestamo;

  datosTipoPrestamo = {
    id: null,
    descripcion: ''
  };

  inputRut: string;
  datosSocio: {
    id: '',
    rut: '',
    socio: '',
    foto: ''
  }
  nombreSocioTest = "";
  rutSocioTest = "";
  errorSocio = false;
  mensajeError = '';

  //datos para enviar al formulario
  datosEnvioPrestamo: Prestamos = {
    fecha: "",
    selectId: "1",
    socioId: "",
    numeroDocumento: "",
    archivoDocumento: "",
    montoPrestamo: "",
    checkAbono: false,
    cuotas: "",
    montoDia: "",
    montoTri: "",
    montoCon: "",
    checkDia: false,
    checkTri: false,
    checkCon: false
  }

  inputGanancia: number;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _sindicalService: SindicalService, private _sociosService: SociosService) {
    config.backdrop = 'static';
    config.keyboard = false;

  }
  ngOnInit() {
    //Guardar Tipo Prestamo
    this._sindicalService.getTipoPrestamo().subscribe((res) => {
      this.selectTipoPrestamo = res.map(({id, descripcion}) => ({id, descripcion}));
      this.datosTipoPrestamo.descripcion = "salud";
    },
    error => {
      console.log(error);
    }
   )
  }

  openPrestamo(Prestamo) {
    this.modalService.open(Prestamo, { size: 'lg' });
  }

  changeTipoPrestamo(evento){
    this.datosTipoPrestamo.id = evento.target.value;
    this.datosEnvioPrestamo.selectId = this.datosTipoPrestamo.id;
    console.log(this.datosEnvioPrestamo.selectId);
    if(this.datosTipoPrestamo.id == 1){
      this.datosTipoPrestamo.descripcion = "salud";
    }else if(this.datosTipoPrestamo.id == 2){
      this.datosTipoPrestamo.descripcion = "apuroEconomico";
      this.datosEnvioPrestamo.montoPrestamo = "100000";
      this.datosEnvioPrestamo.cuotas = "4";
    }else if(this.datosTipoPrestamo.id == 3){
      this.datosTipoPrestamo.descripcion = "aporteEconomico";
    }
  }

  consultarSocio(rut){
    console.log("estoy pasando por aquí: " + rut);
    this._sociosService.getSocio(rut).subscribe((res) => {
      if(res.estado == 'failed'){
        console.log(res.mensaje);
        this.mensajeError = res.mensaje;
        this.nombreSocioTest = "";
        this.rutSocioTest = "";
        this.errorSocio = true;
      }else{
        this.datosSocio = res;
        this.nombreSocioTest = this.datosSocio[0].socio;
        this.rutSocioTest = this.datosSocio[0].rut;
        this.datosEnvioPrestamo.socioId = this.datosSocio[0].id;
        this.errorSocio = false;
      }
    },
    error => {
      console.log(error);
    }
   )
  }

  changeRadioAbono(val){
    console.log(val);
    console.log(this.datosEnvioPrestamo.checkAbono);
    if(val == false){
      this.datosEnvioPrestamo.checkDia = false;
      this.datosEnvioPrestamo.checkTri = false;
      this.datosEnvioPrestamo.checkCon = false;
      this.datosEnvioPrestamo.montoDia = "";
      this.datosEnvioPrestamo.montoTri = "";
      this.datosEnvioPrestamo.montoCon = "";
    }
  }

  changeCheckAbono(val){
    if(val == 1){
      console.log("monto del dia en 0");
      this.datosEnvioPrestamo.montoDia = "";
    }else if(val == 2){
      this.datosEnvioPrestamo.montoTri = "";
    }else if(val == 3){
      this.datosEnvioPrestamo.montoCon = "";
    }
  }

  onSubmit(){
    console.log(this.datosEnvioPrestamo);
    this._sindicalService.setPrestamo(this.datosEnvioPrestamo).subscribe((res) => {
      console.log(res);
    },
    error => {
      console.log(error);
    }
   );
  }

  calcularInteres(event){
    let valor = Math.ceil(event.target.value * 0.10);
    this.inputGanancia = valor;
    console.log(this.inputGanancia);
  }

  calcularPrestamoRestante(event){
    let valor = (this.datosEnvioPrestamo.montoPrestamo);
  }

}
