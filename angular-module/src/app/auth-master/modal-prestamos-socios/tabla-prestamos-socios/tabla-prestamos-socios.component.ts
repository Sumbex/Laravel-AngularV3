import { Component, OnInit } from '@angular/core';
import { Meses } from 'src/app/modelos/meses.model';
import { Anios } from 'src/app/modelos/anios.model';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { HttpClient } from '@angular/common/http';
import { Prestamos } from 'src/app/modelos/prestamos.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-prestamos-socios',
  templateUrl: './tabla-prestamos-socios.component.html',
  styleUrls: ['./tabla-prestamos-socios.component.css']
})
export class TablaPrestamosSociosComponent implements OnInit {

  //objetos con valores de los select para ser manipulados
  selectAnio = {
    id: '1',
    descripcion: ''
  }

  selectMes = {
    id: '1',
    descripcion: ''
  }

  modalActualizarPagoSalud = null;

  //variable que almacena todos los prestamos que tiene el servidor
  valoresPrestamosSalud;
  valoresPrestamosApuro;
  valoresPrestamosAporte
  montoDelInteresPagar;
  montoCuotaPagar;
  montoFinalPagar;

  //Variables de carga
  cargandoTabla = false;

  //Variables para bloquear botones
  blockPagoAbono = false;
  blockPagoPrestamo = false;

  constructor(private _sindicalService: SindicalService,
    private _fechasService: AniosService,
    config: NgbModalConfig,
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

  openActualizar(Actualizar, interes, totalPrestamoNoInteres, totalPrestamo, cuotaP) {
    this.modalActualizarPagoSalud = this.modalService.open(Actualizar, { size: 'sm' });

    console.log(interes, totalPrestamoNoInteres, totalPrestamo, cuotaP);
    this.montoDelInteresPagar = interes / cuotaP;
    this.montoCuotaPagar = Math.ceil(totalPrestamoNoInteres / cuotaP);
    this.montoFinalPagar = totalPrestamo / cuotaP;

    console.log("Monto del interes pagar: " + this.montoDelInteresPagar, "montoCuotaPagar: " + this.montoCuotaPagar, "montoFinalPagar: " + this.montoFinalPagar);
  }

  cerrarActualizar() {
    this.modalActualizarPagoSalud.close();
  }

  refrescarTablaPrestamosClientes() {
    this.cargandoTabla = true;
    this._sindicalService.getPrestramosSocios(this.selectAnio.id, this.selectMes.id).subscribe(
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
  }

  changeAnio(evento) {
    this.selectAnio.id = evento.target.value;
    console.log(this.selectAnio.id);
    this.refrescarTablaPrestamosClientes();
  }

  changeMes(evento) {
    this.selectMes.id = evento.target.value;
    console.log(this.selectMes.id);
    this.refrescarTablaPrestamosClientes();
  }

  pagarPrestamo(fecha, prestamoId, montoPagar) {
    this.blockPagoPrestamo = true;
    this._sindicalService.pagarPrestamo(fecha.value, prestamoId, montoPagar.value).subscribe(
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

  pagarAbono(id, definicionSelectAbono, fecha, monto){
    this.blockPagoAbono = true;
    console.log(id, definicionSelectAbono.value, fecha.value, monto.value);
    this._sindicalService.pagarAbono(id, definicionSelectAbono.value, fecha.value, monto.value).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.blockPagoAbono = false;
          this.refrescarTablaPrestamosClientes();
          alert("Ha ocurrido un error compruebe que los datos sean validos");
        }else{
          this.cerrarActualizar();
          this.blockPagoAbono = false;
          alert("Se ha realizado el pago correctamente");
        }
      },
      error => {
        console.log(error);
        this.blockPagoAbono = false;
        alert("Ha ocurrido un error");
      }
    )
  }
}
