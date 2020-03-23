import { TestBed } from '@angular/core/testing';

import { LiquidacionEmpanadaService } from './liquidacion-empanada.service';

describe('LiquidacionEmpanadaService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: LiquidacionEmpanadaService = TestBed.get(LiquidacionEmpanadaService);
    expect(service).toBeTruthy();
  });
});
