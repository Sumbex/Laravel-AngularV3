import { TestBed } from '@angular/core/testing';

import { LiquidacionMarroService } from './liquidacion-marro.service';

describe('LiquidacionMarroService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: LiquidacionMarroService = TestBed.get(LiquidacionMarroService);
    expect(service).toBeTruthy();
  });
});
