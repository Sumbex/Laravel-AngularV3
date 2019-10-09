import { Component, OnInit } from '@angular/core';
import { AniosService } from 'src/app/servicios/anios.service';
import { ConsorcioService } from 'src/app/servicios/consorcio.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-fondo-mutuo',
  templateUrl: './tabla-fondo-mutuo.component.html',
  styleUrls: ['./tabla-fondo-mutuo.component.css']
})
export class TablaFondoMutuoComponent implements OnInit {

  socios;
  sociosMensual;
  sociosAnual;
  search: string = '';
  mod_editar = null;
  mod_validar = null;
  blockLoad = false;
  blockLoad2 = false;
  contador = 3;

  //actualizar_socio
  rut;
  nombres;
  a_paterno;
  a_materno;
  fecha_nacimiento;
  fecha_ingreso;
  fecha_egreso;

  //para validar usuario
  user: object = [];
  load: boolean = false;
  modalReference = null;
  m_val = null;
  mod_opcion = null;

  closeResult: string;
  pass: string = '';
  btn_validar: boolean = false;

  currentLesson: string;

  buttonStatus = false;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  // estado_socio: object = ['estado'];
  ver_load: boolean = true;
  ver_estado_soc: boolean = false;

  //variables select año y mes
  anio; anios;
  mes; meses;
  idAnioActual;
  idMesActual;
  suc_res1 = false;
  suc_res2 = false;
  //ingresar consorcio
  blockIngreso: boolean = false;

  //pago beneficio
  modalPagoBeneficio;
  tipoPago = 2;
  tipoRetiro = '0';
  tipoPorc = '1';
  mesBeneficio;
  montoBeneficio;
  montoFinal;
  socioPB;
  actualizarLoad = false;
  actualizarLoad2 = false;
  socio = '';
  

  constructor(
    private _time: AniosService,
    private _consorcioService: ConsorcioService,
    config: NgbModalConfig,
    private modalService: NgbModal) {
      config.backdrop = 'static';
      config.keyboard = false;

  }

  ngOnInit() {

    if (localStorage.getItem('token') == '') {
      alert("La sesión ya expiro!");

      location.reload();
    }

    this.llenar_select();
  }
  openPagoBeneficio(PagoBeneficio) {
    this.modalPagoBeneficio = this.modalService.open(PagoBeneficio, { size: 'lg' });
    this.socioDesvinculado();
  }

  cerrarPagoBeneficio() {
    this.modalPagoBeneficio.close();
  }
  limpiarPagoBeneficio(){
    this.tipoPorc = '1';
    this.tipoRetiro = '0';
    this.montoBeneficio = '';
    this.socio = '';
  }

  listar() {
    this.blockLoad2 = true;
    this._consorcioService.getTablaConsorcios(this.anio).subscribe(
      response => {
        console.log(response);
        this.socios = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  listarMensual() {
    // this.blockLoad2 = true;
    this._consorcioService.getTablaConsorciosTotalesMensuales(this.anio).subscribe(
      response => {
        console.log(response);
        this.sociosMensual = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }
  
  listarAnual() {
    // this.blockLoad2 = true;
    this._consorcioService.getTablaConsorciosTotalAnual(this.anio).subscribe(
      response => {
        console.log(response);
        this.sociosAnual = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  filtrar() {

    this.blockLoad = true;
    if (this.search == '') {
      alert("Ingrese un nombre para filtrar");
      this.blockLoad = false;
      return false;
    } else {
      this._consorcioService.getTablaConsorcio(this.anio, this.search).subscribe(
        response => {
          console.log(response);
          this.socios = response;
          this.blockLoad = false;

        }
      )
    }
  }

  llenar_select() {

    this._time.getAnios().subscribe(
      response => {
        this.anios = response;


        this._time.getAnioActual().subscribe(
          (response: { id }) => {
            this.anio = response.id;
            this.suc_res1 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);

          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )

    this._time.getMeses().subscribe(
      response => {
        this.meses = response;

        this._time.getMesActual().subscribe(
          (response: { id }) => {
            this.mes = response.id;
            this.suc_res2 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);
          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )
  }

  listo_para_listar(res1, res2) {
    if (res1 == true && res2 == true) {
      this.listar();
      this.listarMensual();
      this.listarAnual();
    }
  }

  btn_reload() {

    this.contador=3;
    this.listo_para_listar(this.suc_res1, this.suc_res2);

  }

  ConvertToInt(val){
    return parseInt(val);
  }

  calcularPagoBeneficio() {
    this.actualizarLoad = true;
    this._consorcioService.calcularPagoBeneficio(this.tipoPorc,this.mesBeneficio,this.anio).subscribe((response) => {
      if(response.estado == 'success'){
        alert(response.mensaje);
        this.actualizarLoad = false;
        this.montoBeneficio = response.monto_beneficio;
        document.getElementById('refrescarTabla').click();
      }
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );
  }

  guardarSocioDesv() {
    this.actualizarLoad2 = true;
    this._consorcioService.guardarPagoBeneficio(this.socio,this.tipoPorc,this.tipoRetiro,this.mesBeneficio,this.anio,this.montoFinal).subscribe((response) => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.actualizarLoad2 = false;
      }
      if (response.estado == 'success') {
        this.socio = '';
        this.tipoPorc = '0';
        this.tipoRetiro = '0';
        this.mesBeneficio = '';
        this.montoBeneficio = '';
        this.montoFinal = '';
        alert(response.mensaje);
        this.actualizarLoad2 = false;
        this.modalPagoBeneficio.close();
      }
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );
  }

  socioDesvinculado(){
    this._consorcioService.socios_sin_pb().subscribe((response) => {
        this.socioPB = response;
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );
  }


  // rescatarDiaSueldo(id){
  //   this.valorBeneficio = id;
  // }

  // calcularPorcentaje(){
  //   if(this.tipoDescuento == 1){
  //     // console.log("estoy aqui 1");
  //     this.calcularBeneficio = this.valorBeneficio;
  //   }else if(this.tipoDescuento == 2){
  //     // console.log("estoy aqui 2");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.90); 
    
  //   }else if(this.tipoDescuento == 3){
  //     // console.log("estoy aqui 3");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.75); 
    
  //   }else if(this.tipoDescuento == 4){
  //     // console.log("estoy aqui 4");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.60); 
    
  //   }else if(this.tipoDescuento == 5){
  //     // console.log("estoy aqui 5");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.50); 
    
  //   }else if(this.tipoDescuento == 6){
  //     // console.log("estoy aqui 6");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.40); 
    
  //   }else if(this.tipoDescuento == 7){
  //     // console.log("estoy aqui 7");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.30); 
    
  //   }else if(this.tipoDescuento == 8){
  //     // console.log("estoy aqui 8");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.25); 
    
  //   }else if(this.tipoDescuento == 9){
  //     // console.log("estoy aqui 9");
  //     this.calcularBeneficio = (this.valorBeneficio * 0.20); 
  //   }
  // }

}
