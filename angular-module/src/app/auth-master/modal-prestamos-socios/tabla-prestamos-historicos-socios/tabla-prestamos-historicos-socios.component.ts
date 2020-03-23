import { Component, OnInit } from '@angular/core';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-prestamos-historicos-socios',
  templateUrl: './tabla-prestamos-historicos-socios.component.html',
  styleUrls: ['./tabla-prestamos-historicos-socios.component.css']
})
export class TablaPrestamosHistoricosSociosComponent implements OnInit {

  //objetos con valores de los select para ser manipulados
  selectAnio = {
    id: '1',
    descripcion: ''
  }

  selectMes = {
    id: '1',
    descripcion: ''
  }

  //variable que almacena todos los prestamos que tiene el servidor
  valoresPrestamosSalud;
  valoresPrestamosApuro;
  valoresPrestamosAporte
  montoDelInteresPagar;
  montoCuotaPagar;
  montoFinalPagar;
  blockPagoPrestamo;
  blockPagoAbono;
  get_abonos;
  cargar_1:boolean=true;
  mostrar_1:boolean=false;
  resumen:any = [
        'egreso',
        'ingreso',
        'abono',
        'interes'
  ];
  get_pni;
  get_pc;
  calculo_cuota;
  s_abono:string='1';
  m_abono;
  if_abono:boolean=true;

  //Variables de carga
  cargandoTabla = false;
   modalActualizarPagoSalud = null;
   m_bono = null;
   m_pni = null;
   m_pc = null;

  constructor(private _sindicalService: SindicalService, private _fechasService: AniosService, config: NgbModalConfig,
    private modalService: NgbModal) {
      config.backdrop = 'static';
      config.keyboard = false;
    }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectAnio.id = '1';

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.selectMes.id = '1';

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        let AnioActual;
        AnioActual = response;
        this.selectAnio.id = AnioActual.id;
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        let mesActual;
        mesActual = response;
        this.selectMes.id = mesActual.id;
        this.refrescarTablaPrestamosClientes();
        
      },
      error => {
        console.log(error);
      }
    )
  }

  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  refrescarTablaPrestamosClientes() {
    this.cargandoTabla = true;
    this._sindicalService.getPrestamosHistoricos(this.selectAnio.id, this.selectMes.id).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.valoresPrestamosSalud = null;
          this.valoresPrestamosApuro = null;
          this.valoresPrestamosAporte = null;
          this.cargandoTabla = false;
          alert(response.mensaje);
        } else {
          this.valoresPrestamosSalud = response.salud;
          this.valoresPrestamosApuro = response.apuro;
          this.valoresPrestamosAporte = response.aporte;
          this.cargandoTabla = false;
        }
      },
      error => {
        console.log(error);
      }
    )
    this.listar_resumen_prestamos();
  }

  changeAnio(evento) {
    this.selectAnio.id = evento.target.value;
    console.log(this.selectAnio.id);
    this.refrescarTablaPrestamosClientes();
    this.listar_resumen_prestamos();
  }

  changeMes(evento) {
    this.selectMes.id = evento.target.value;
    console.log(this.selectMes.id);
    this.refrescarTablaPrestamosClientes();
    //this.listar_resumen_prestamos();
  }

  pagarPrestamo(fecha, prestamoId, montoPagar) {
    this.blockPagoPrestamo = true;
    this._sindicalService.pagarPrestamoSalud(fecha.value, prestamoId, montoPagar.value).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.blockPagoPrestamo = false;
          alert(response.mensaje);
        } else {
          this.cerrarActualizar();
          this.blockPagoPrestamo = false;
          alert("Se ha realizado el pago correctamente");
        }
      },
      error => {
        this.blockPagoPrestamo = false;
        alert("Ha ocurrido un error");
      }
    )
  }
  
   pagarPrestamoApu(fecha, prestamoId, montoPagar) {
    this.blockPagoPrestamo = true;
    this._sindicalService.pagarPrestamoApuro(fecha.value, prestamoId, montoPagar.value).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.blockPagoPrestamo = false;
          alert(response.mensaje);
        } else {
          this.cerrarActualizar();
          this.blockPagoPrestamo = false;
          alert("Se ha realizado el pago correctamente");
        }
      },
      error => {
        this.blockPagoPrestamo = false;
        alert("Ha ocurrido un error");
      }
    )
  }

  openActualizar(Actualizar, interes, totalPrestamoNoInteres, totalPrestamo, cuotaP) {
    this.modalActualizarPagoSalud = this.modalService.open(Actualizar, { size: 'sm' });

    console.log(interes, totalPrestamoNoInteres, totalPrestamo, cuotaP);
    this.montoDelInteresPagar = Math.round(interes / cuotaP);
    this.montoCuotaPagar = Math.ceil(totalPrestamoNoInteres / cuotaP);
    this.montoFinalPagar = Math.round(totalPrestamo / cuotaP);

    console.log("Monto del interes pagar: " + this.montoDelInteresPagar, "montoCuotaPagar: " + this.montoCuotaPagar, "montoFinalPagar: " + this.montoFinalPagar);

  }

  cerrarActualizar() {
    this.modalActualizarPagoSalud.close();
  }

  openAbono(modal,id,tipo){
    this.mostrar_1 = false;
    this.cargar_1 = true;
    this.m_bono = this.modalService.open(modal, { size: 'lg' });
    this.abonos(id,tipo)
  }

  pagarAbono(id, definicionSelectAbono, fecha, monto){
    this.blockPagoAbono = true;
    console.log(id, definicionSelectAbono.value, fecha.value, monto.value);
    this._sindicalService.pagarAbono(id, definicionSelectAbono.value, fecha.value, monto.value).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.blockPagoAbono = false;
          this.refrescarTablaPrestamosClientes();
          //this.listar_resumen_prestamos();
          alert(""+response.mensaje+"");
        }else{
          this.cerrarActualizar();
          this.blockPagoAbono = false;
          alert(""+response.mensaje+"");
        }
      },
      error => {
        console.log(error);
        this.blockPagoAbono = false;
        alert("Error de proceso");
      }
    )
  }
  abonos(id, tipo){
    this._sindicalService.listar_abonos_por_prestamo(id,tipo).subscribe(
      response => {
          this.get_abonos = response;
          this.cargar_1 = false;
          this.mostrar_1 = true;
      },
      error => {
        console.log(error);
        alert("No se encontraron datos");
        this.get_abonos = "No existen datos";
        this.cargar_1 = false;
        this.mostrar_1 = true;
        this.m_bono.close();
      }
    ) 
  }

  listar_resumen_prestamos(){
    this._sindicalService.lista_ultima_prestamos(this.selectAnio.id, this.selectMes.id).subscribe(
      response => {
          this.resumen = response;
          
      },
      error => {
        console.log(error);
        alert("No se encontraron datos");
        this.get_abonos = "No existen datos";
        
        this.m_bono.close();
      }
    )
  }

  modal_pni(modal){
    this.m_pni = this.modalService.open(modal, { size: 'xl' });
    this.listar_prestamos_no_iniciados();
  }

  modal_pc(modal){
    this.m_pc = this.modalService.open(modal, { size: 'xl' });
    this.listar_prestamos_finalizados();
  }

  listar_prestamos_no_iniciados(){
    this._sindicalService.listar_pni().subscribe(
      response => {
          if (response.estado =="success") {
            this.get_pni = response.body;
          }
          
      },
      error => {
        console.log(error);
        alert("No se encontraron datos");
        this.get_abonos = "No existen datos";
        
        this.m_pc.close();
      }
    )
  }
  listar_prestamos_finalizados(){
    this._sindicalService.listar_pc().subscribe(
      response => {
          if (response.estado =="success") {
            this.get_pc = response.body;
          }
          
      },
      error => {
        console.log(error);
        alert("No se encontraron datos");
        this.get_pc = "No existen datos";
        
        this.m_bono.close();
      }
    )
  }

  calcular_cuota_prestamo2(id){
    this._sindicalService.calcular_cuota(id).subscribe(
      response => {
          if (response.estado =="success") {
            this.calculo_cuota = response.calculo_cuota;
          }
          
      },
      error => {
        console.log(error);
        alert("No se encontraron datos");
        
      }
    )
  }
  calcular_el_abono(id){
    this.m_abono = '';
    this.if_abono = true;
    this._sindicalService.calcular_abono(id,this.s_abono).subscribe(
      response => {
          if (response.estado =="success") {
            this.m_abono = response.monto;
            this.if_abono = false;
            console.log(this.if_abono);
          }

          
      },
      error => {
        this.m_abono = '';
        this.if_abono = false;
        console.log(error);
        alert("No se encontraron datos");
        
      }
    )
  }

  changeAbono(id){

      this.calcular_el_abono(id);
  }

}
