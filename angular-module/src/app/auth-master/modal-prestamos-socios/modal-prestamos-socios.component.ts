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
    id: 0,
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

  //datos para enviar al formulario
  datosEnvioPrestamo: Prestamos = {
    fecha: "",
    selectId: 1,
    socioId: "",
    numeroDocumento: "",
    archivoDocumento: null,
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

  blockPrestamo = false;
  inputPrestamoRestante: number;
  inputGanancia: number;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _sindicalService: SindicalService, private _sociosService: SociosService) {
    config.backdrop = 'static';
    config.keyboard = false;

  }
  ngOnInit() {
    //Guardar Tipo Prestamo
    this._sindicalService.getTipoPrestamo().subscribe((res) => {
      this.selectTipoPrestamo = res.map(({id, descripcion}) => ({id, descripcion}));
      this.datosTipoPrestamo.descripcion = "";
    },
    error => {
      console.log(error);
    }
   )
  }

  openPrestamo(Prestamo) {
    this.modalService.open(Prestamo, { size: 'xl' });
  }

  onSelectImage(event) {
    this.datosEnvioPrestamo.archivoDocumento = event.srcElement.files[0];
  }

  changeTipoPrestamo(evento){
    this.datosTipoPrestamo.id = evento.target.value;
    this.datosEnvioPrestamo.selectId = this.datosTipoPrestamo.id;
    console.log(this.datosEnvioPrestamo.selectId);
    if(this.datosTipoPrestamo.id == 1){
      this.datosTipoPrestamo.descripcion = "salud";
    }else if(this.datosTipoPrestamo.id == 2){
      this.datosTipoPrestamo.descripcion = "apuroEconomico";
      this.datosEnvioPrestamo.montoPrestamo = "";
      this.datosEnvioPrestamo.cuotas = "";
    }else if(this.datosTipoPrestamo.id == 3){
      this.datosTipoPrestamo.descripcion = "aporteEconomico";
      this.datosEnvioPrestamo.cuotas = "0";
    }
  }

  consultarSocio(rut){
    this.blockPrestamo = true;
    this._sociosService.getSocio(rut).subscribe((res) => {
      if(res.estado == 'failed'){
        alert(res.mensaje);
        this.nombreSocioTest = "";
        this.rutSocioTest = "";
        this.errorSocio = true;
        this.blockPrestamo = false;
      }else{
        this.datosSocio = res;
        this.nombreSocioTest = this.datosSocio[0].socio;
        this.rutSocioTest = this.datosSocio[0].rut;
        this.datosEnvioPrestamo.socioId = this.datosSocio[0].id;
        this.errorSocio = false;
        this.blockPrestamo = false;
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
    this.blockPrestamo = true;
    console.log(this.blockPrestamo);
    console.log(this.datosEnvioPrestamo);
    this._sindicalService.setPrestamo(this.datosEnvioPrestamo).subscribe((res) => {
      console.log(res);
      if(res.estado == 'failed_v' || res.estado == 'failed'){
        //alert('ERROR: Compruebe que los valores ingresados son correctos');
        let mensaje = JSON.stringify(res.mensaje);
        mensaje = mensaje.replace(/[\[\]'"{}]+/g,'')
        alert(mensaje);
        this.blockPrestamo = false;
      }else{
        alert('Ingreso correcto');
        this.blockPrestamo = false;
        //limpieza de campos
        this.datosTipoPrestamo.id = 0;
        this.datosEnvioPrestamo.fecha = "";
        this.datosEnvioPrestamo.selectId = 1;
        this.datosEnvioPrestamo.socioId = "";
        this.datosEnvioPrestamo.numeroDocumento = "";
        this.datosEnvioPrestamo.archivoDocumento = null;
        this.datosEnvioPrestamo.montoPrestamo = "";
        this.datosEnvioPrestamo.checkAbono = false;
        this.datosEnvioPrestamo.cuotas = "";
        this.datosEnvioPrestamo.montoDia = "";
        this.datosEnvioPrestamo.montoTri = "";
        this.datosEnvioPrestamo.montoCon = "";
        this.datosEnvioPrestamo.checkDia = false;
        this.datosEnvioPrestamo.checkTri = false;
        this.datosEnvioPrestamo.checkCon = false;
        this.nombreSocioTest = "";
        this.rutSocioTest = "";
        this.inputRut = "";
        this.inputPrestamoRestante = null;
      }
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

  calcularPrestamoRestante(){
    let montoDia: number;
    let montoTri: number;
    let montoCon: number;

    montoDia = parseInt(this.datosEnvioPrestamo.montoDia);
    montoTri = parseInt(this.datosEnvioPrestamo.montoTri);
    montoCon = parseInt(this.datosEnvioPrestamo.montoCon);

    if(this.datosEnvioPrestamo.montoPrestamo == '' || this.datosEnvioPrestamo.montoPrestamo == null){
      this.datosEnvioPrestamo.checkAbono = false;
      this.changeRadioAbono(false);
    }
    if(this.datosEnvioPrestamo.montoDia == '' || this.datosEnvioPrestamo.montoDia == null){
      montoDia = 0;
      console.log("montoDia" + montoDia);
    }
    if(this.datosEnvioPrestamo.montoTri == '' || this.datosEnvioPrestamo.montoTri == null){
      montoTri = 0;
      console.log("montoTri" + montoTri);
    }
    if(this.datosEnvioPrestamo.montoCon == '' || this.datosEnvioPrestamo.montoCon == null){
      montoCon = 0;
      console.log("montoCon" + montoCon);
    }

    let valor = (parseInt(this.datosEnvioPrestamo.montoPrestamo) - montoDia - montoTri - montoCon);
    if(valor < 0){
      alert("monto restante negativo");
    }
    this.inputPrestamoRestante = valor;
    if(this.inputPrestamoRestante == 0){
      this.datosEnvioPrestamo.cuotas = "0";
    }
  }

}
